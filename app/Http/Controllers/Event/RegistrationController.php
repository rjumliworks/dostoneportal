<?php

namespace App\Http\Controllers\Event;

use Hashids\Hashids;
use App\Models\Participant;
use App\Models\ParticipantDetail;
use App\Models\ParticipantFace;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventSessionDetail;
use App\Models\ListName;
use App\Jobs\RegistrationJob;
use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\SessionEvent;
use App\Events\CapacityEvent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\Exception\RekognitionException;
use Illuminate\Database\UniqueConstraintViolationException;
use App\Http\Requests\Event\ParticipantRequest;
use App\Http\Resources\Api\Events\Session\ParticipantResource;

class RegistrationController extends Controller
{
    use HandlesTransaction;

    public function store(ParticipantRequest $request){
        // The frontend already hides the form entirely when a session's
        // pre-registration hasn't opened yet — this is just the server-side
        // backstop against a stale page or a direct POST to /register.
        if ($request->session_id && !EventSession::where('id', $request->session_id)->value('is_prereg')) {
            return back()->withErrors(['avatar' => 'Pre-registration for this session is not yet open.']);
        }

        // VIP/Special Guest registrations (type_id 198) are exempt from the
        // capacity waitlist and are auto-approved instead of landing as
        // Pending — they get a guaranteed seat, not a slot in the queue.
        $isVip = (int) $request->type_id === 198;

        // Captured by reference: HandlesTransaction::handleTransaction() only
        // passes through data/message/info/status from the callback's return
        // value, so this is how the redirect logic below finds out whether
        // the registration landed as Reserved.
        $isReserved = false;

        $result = $this->handleTransaction(function () use ($request, &$isReserved, $isVip) {
            $participant = Participant::create(array_merge($request->except('avatar'), [
                'code' => $this->generateCode(),
                'is_completed' => 1
            ]));

            if ($participant) {
                if ($request->session_id && !$isVip) {
                    // Safety net against overbooking: the frontend already
                    // flags sessionFull once it sees capacity reached via the
                    // CapacityEvent broadcast, but two submissions racing at
                    // the last open slot could both pass that client-side
                    // check. lockForUpdate() serializes the count within this
                    // transaction so only one gets the real seat — anyone
                    // past capacity is registered as Reserved (waitlisted)
                    // instead of a normal Pending seat, but stays tied to the
                    // session either way.
                    $capacity = EventSessionDetail::where('session_id', $request->session_id)->value('capacity');

                    if ($capacity) {
                        $currentCount = EventSessionParticipant::where('session_id', $request->session_id)
                            ->whereNotIn('status_id', EventSessionParticipant::CAPACITY_EXCLUDED_STATUSES)
                            ->lockForUpdate()
                            ->count();

                        if ($currentCount >= $capacity) {
                            $isReserved = true;
                        }
                    }
                }

                if($request->session_id){
                    EventSessionParticipant::create([
                        'status_id' => $isVip ? 58 : ($isReserved ? 60 : 52), // Approved (VIP) : Reserved : Pending
                        'participant_id' => $participant->id,
                        'session_id' => $request->session_id,
                        'is_approved' => $isVip ? 1 : 0,
                    ]);
                }
                $detail_data = $request->except('captcha', 'avatar', 'signature');

                if (($detail_data['affiliation_id'] ?? null) === 'others') {
                    $detail_data['affiliation_id'] = ListName::firstOrCreate(
                        ['name' => 'Others', 'type' => 'Affiliation'],
                        ['is_active' => 1]
                    )->id;
                }

                $signature_path = null;
                $avatar_path = null;

                try {
                    if ($request->hasFile('signature')) {
                        $signature_file = $request->file('signature');
                        $signature_name = $participant->code.'.'.$signature_file->getClientOriginalExtension();
                        $signature_path = $signature_file->storeAs('participants/'.$participant->code.'/signature/', $signature_name, 'public');
                        $detail_data['signature'] = $signature_path;
                    }

                    $avatar_hash = null;

                    if ($request->hasFile('avatar')) {
                        $avatar_hash = hash_file('sha256', $request->file('avatar')->getRealPath());
                        $avatar_path = $this->uploadAvatar($participant, $request->file('avatar'));
                        $detail_data['avatar'] = $avatar_path;
                    }

                    $detail = $participant->detail()->create($detail_data);

                    if ($avatar_path) {
                        $this->indexFace($participant, $detail, $avatar_path, $avatar_hash);
                    }
                } catch (\Throwable $e) {
                    // storeAs()/uploadAvatar() write straight to disk/S3 — that's
                    // not covered by DB::transaction()'s rollback, so without this
                    // every rejected attempt (duplicate face, no face detected, a
                    // retake that got resubmitted, etc.) permanently orphans the
                    // file it just uploaded. indexFace() cleans up its own
                    // Rekognition-side face on failure, so only storage is handled
                    // here.
                    if ($avatar_path) {
                        Storage::disk('s3')->delete($avatar_path);
                    }
                    if ($signature_path) {
                        Storage::disk('public')->delete($signature_path);
                    }
                    throw $e;
                }

                $name = ucwords(strtolower($request->firstname.' '.$request->lastname));

                RegistrationJob::dispatch($request->email,$name,$request->session_id,$isReserved,$isVip)->onConnection('database');
            }

            return [
                'data' => $participant,
                'message' => 'User information saved successfully.',
                'info' => "Check you email for verification, Thank you."
            ];
        });

        if (!$result['status']) {
            $error = preg_replace('/^An unexpected error occurred:\s*/', '', $result['info'] ?? $result['message']);

            return back()->withErrors(['avatar' => $error])->with([
                'data' => $result['data'],
                'message' => $result['message'],
                'info' => $result['info'],
                'status' => $result['status'],
            ]);
        }

        // Broadcasting runs after the transaction has committed. QUEUE_CONNECTION
        // is sync and neither event overrides its connection, so these dispatch
        // synchronously to Reverb — doing that inside the transaction meant a
        // Reverb hiccup would roll back the just-created participant while the
        // face indexFace() already registered in Rekognition stayed indexed
        // (AWS has no rollback), leaving an orphaned face that later blocked
        // legitimate registrants as "already registered to another participant".
        if ($request->session_id) {
            $participant = $result['data'];
            $sessionParticipant = EventSessionParticipant::with('participant.detail')
                ->where('participant_id', $participant->id)
                ->where('session_id', $request->session_id)
                ->first();
            broadcast(new SessionEvent(new ParticipantResource($sessionParticipant), 'register'));
            broadcast(new CapacityEvent(
                EventSessionParticipant::where('session_id', $request->session_id)
                    ->whereNotIn('status_id', EventSessionParticipant::CAPACITY_EXCLUDED_STATUSES)
                    ->count(),
                $request->session_id
            ));
        }

        if (!$request->session_id) {
            return redirect()->route('rstw2026.success.general')->with([
                'data' => $result['data'],
                'message' => $result['message'],
                'info' => $result['info'],
                'status' => $result['status'],
                'reserved' => false,
            ]);
        }

        $hashids = new Hashids('krad',10);
        $key = urlencode(Crypt::encryptString($hashids->encode($request->session_id)));

        return redirect()->route($isVip ? 'rstw2026.successvip' : 'rstw2026.success', ['key' => $key])->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
            'reserved' => $isReserved,
        ]);
    }

    /**
     * Attach an already-registered participant (identified via the email-OTP
     * flow, see Api\Events\AuthController::verify) to another session,
     * without re-collecting their avatar/signature/details. Mirrors store()'s
     * pre-reg and capacity/waitlist rules so a returning participant can't
     * skip the waitlist that a first-time registrant would hit.
     */
    public function registerExisting(Request $request){
        $request->validate([
            'session_id' => 'required|exists:event_sessions,id',
        ]);

        $participant = $request->user();

        if (!EventSession::where('id', $request->session_id)->value('is_prereg')) {
            return response()->json(['status' => false, 'message' => 'Pre-registration for this session is not yet open.'], 422);
        }

        $alreadyRegistered = EventSessionParticipant::where('participant_id', $participant->id)
            ->where('session_id', $request->session_id)
            ->exists();

        if ($alreadyRegistered) {
            return response()->json(['status' => false, 'message' => 'You are already registered for this session.'], 422);
        }

        $isReserved = false;

        $result = $this->handleTransaction(function () use ($request, $participant, &$isReserved) {
            $capacity = EventSessionDetail::where('session_id', $request->session_id)->value('capacity');

            if ($capacity) {
                $currentCount = EventSessionParticipant::where('session_id', $request->session_id)
                    ->whereNotIn('status_id', EventSessionParticipant::CAPACITY_EXCLUDED_STATUSES)
                    ->lockForUpdate()
                    ->count();

                if ($currentCount >= $capacity) {
                    $isReserved = true;
                }
            }

            EventSessionParticipant::create([
                'status_id' => $isReserved ? 60 : 52, // Reserved : Pending
                'participant_id' => $participant->id,
                'session_id' => $request->session_id,
                'is_approved' => 0,
            ]);

            $data = EventSessionParticipant::with('participant.detail')
                ->where('participant_id', $participant->id)
                ->where('session_id', $request->session_id)
                ->first();
            broadcast(new SessionEvent(new ParticipantResource($data), 'register'));
            broadcast(new CapacityEvent(
                EventSessionParticipant::where('session_id', $request->session_id)
                    ->whereNotIn('status_id', EventSessionParticipant::CAPACITY_EXCLUDED_STATUSES)
                    ->count(),
                $request->session_id
            ));

            $name = ucwords(strtolower($participant->firstname.' '.$participant->lastname));
            RegistrationJob::dispatch($participant->email, $name, $request->session_id, $isReserved, false)->onConnection('database');

            return [
                'data' => $data,
                'message' => 'Registration submitted successfully.',
                'info' => 'Registration submitted successfully.',
            ];
        });

        if (!$result['status']) {
            $error = preg_replace('/^An unexpected error occurred:\s*/', '', $result['info'] ?? $result['message']);
            return response()->json(['status' => false, 'message' => $error], 422);
        }

        $hashids = new Hashids('krad', 10);
        $key = urlencode(Crypt::encryptString($hashids->encode($request->session_id)));

        return response()->json([
            'status' => true,
            'reserved' => $isReserved,
            'redirect' => route('rstw2026.success', ['key' => $key]),
        ]);
    }

    public function avatar(Request $request){
        try {
            $request->validate([
                'image' => 'required|image|max:2048', // Assuming maximum file size is 2MB
            ]);

            $participant = Participant::with('detail')->findOrFail($request->id);
            $imageHash = hash_file('sha256', $request->file('image')->getRealPath());
            $s3Path = $this->uploadAvatar($participant, $request->file('image'));

            $participant->detail->avatar = $s3Path;
            $participant->detail->save();

            $this->indexFace($participant, $participant->detail, $s3Path, $imageHash);

            return response()->json([
                'status'  => true,
                'message' => 'Profile updated successfully',
                'data'    => $s3Path
            ]);

        }catch(\Throwable $th){

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    private function uploadAvatar(Participant $participant, UploadedFile $file){
        $extension = $file->getClientOriginalExtension();
        $filename = strtolower($participant->code).'.'.$extension;
        return $file->storeAs('oneportal/participants', $filename, 's3');
    }

    /**
     * Index the avatar as the participant's face in Rekognition. Any faces
     * previously indexed for this participant are removed first, so
     * re-uploading an avatar replaces the old face instead of stacking a new one.
     *
     * Throws on any Rekognition failure, including "no face detected" and
     * "face already belongs to someone else" — the caller (store()) relies
     * on this to roll back the whole registration instead of saving a
     * participant with no usable face record.
     *
     * participant_faces.participant_id is a FK into participant_details, not
     * participants — always pass the detail row's id, not $participant->id.
     *
     * $imageHash (sha256 of the uploaded bytes) serializes concurrent calls
     * for the exact same photo: two submissions carrying identical image
     * bytes (double-click, retry, or a stale capture resubmitted) would
     * otherwise both pass the "not a duplicate" check below before either
     * had committed, then collide on the unique image_id when both tried to
     * index. Locking on the content hash means the second one waits for the
     * first to finish — succeed or fail — so it sees accurate state instead
     * of racing it.
     */
    private function indexFace(Participant $participant, ParticipantDetail $detail, string $s3Path, string $imageHash){
        try {
            return \Cache::lock('face-index:'.$imageHash, 30)->block(15, function () use ($participant, $detail, $s3Path) {
                $rekognition = new RekognitionClient([
                    'version' => 'latest',
                    'region'      => config('services.rekognition.region'),
                    'credentials' => [
                        'key'    => config('services.rekognition.key'),
                        'secret' => config('services.rekognition.secret'),
                    ],
                ]);

                $collectionId = config('services.rekognition.participant_id');
                $image = [
                    'S3Object' => [
                        'Bucket' => config('services.rekognition.bucket'),
                        'Name' => $s3Path,
                    ],
                ];

                try {
                    $matches = $rekognition->searchFacesByImage([
                        'CollectionId' => $collectionId,
                        'Image' => $image,
                        'FaceMatchThreshold' => 90,
                        'MaxFaces' => 1,
                    ]);
                } catch (RekognitionException $e) {
                    if ($e->getAwsErrorCode() === 'InvalidParameterException') {
                        throw new \RuntimeException('No face was detected in your avatar. Please retake your photo with your face clearly visible.');
                    }
                    throw $e;
                }

                $match = $matches['FaceMatches'][0] ?? null;
                $matchedId = $match['Face']['ExternalImageId'] ?? null;

                if ($match && $matchedId !== (string) $participant->id) {
                    if ($matchedId !== null && Participant::where('id', $matchedId)->exists()) {
                        throw new \RuntimeException('This face is already registered to another participant. Please use your own photo.');
                    }

                    // Stale/orphaned face: indexed by an earlier attempt that
                    // failed/rolled back after IndexFaces() had already committed
                    // it on the AWS side (no DB transaction covers that call —
                    // see the cleanup below and in store()). Nothing in the DB
                    // owns it, so delete it and continue indexing this
                    // participant's photo instead of blocking them over a ghost
                    // record. Narrow race: if $matchedId belongs to a
                    // registration that's still mid-transaction (not yet
                    // committed), this won't see it and could delete a face
                    // that's about to become legitimate — rare in practice since
                    // it needs two near-simultaneous submissions of the literal
                    // same photo, but worth knowing if this ever misfires.
                    \Log::info('Rekognition: deleting orphaned face with no matching participant.', [
                        'face_id' => $match['Face']['FaceId'],
                        'external_image_id' => $matchedId,
                    ]);

                    $rekognition->deleteFaces([
                        'CollectionId' => $collectionId,
                        'FaceIds' => [$match['Face']['FaceId']],
                    ]);
                }

                $existingFaces = ParticipantFace::where('participant_id', $detail->id)->get();

                if ($existingFaces->isNotEmpty()) {
                    $rekognition->deleteFaces([
                        'CollectionId' => $collectionId,
                        'FaceIds' => $existingFaces->pluck('face_id')->all(),
                    ]);
                    ParticipantFace::whereIn('id', $existingFaces->pluck('id'))->delete();
                }

                $result = $rekognition->indexFaces([
                    'CollectionId' => $collectionId,
                    'Image' => $image,
                    'ExternalImageId' => (string) $participant->id,
                    'DetectionAttributes' => ['DEFAULT'],
                ]);

                if (empty($result['FaceRecords'])) {
                    throw new \RuntimeException('No face was detected in your avatar. Please retake your photo with your face clearly visible.');
                }

                try {
                    foreach ($result['FaceRecords'] as $record) {
                        ParticipantFace::create([
                            'participant_id' => $detail->id,
                            'face_id' => $record['Face']['FaceId'],
                            'image_id' => $record['Face']['ImageId'],
                            'status' => 'active',
                        ]);
                    }
                } catch (\Throwable $e) {
                    // indexFaces() above already committed the face into the
                    // Rekognition collection — AWS has no rollback, so if bookkeeping
                    // it locally fails, undo the AWS side too. Otherwise it's left
                    // permanently indexed under a participant whose registration is
                    // about to be rolled back, and later blocks anyone with a similar
                    // face as "already registered to another participant".
                    $rekognition->deleteFaces([
                        'CollectionId' => $collectionId,
                        'FaceIds' => array_column(array_column($result['FaceRecords'], 'Face'), 'FaceId'),
                    ]);

                    // ImageId is a content hash, not a random id like FaceId — a
                    // collision here means this exact photo was already indexed by
                    // another submission racing this one on the same capture. The
                    // lock above should make that essentially impossible now, but
                    // this stays as a safety net in case a lock is ever missed
                    // (e.g. a future call site that forgets to pass a hash).
                    if ($e instanceof UniqueConstraintViolationException) {
                        throw new \RuntimeException('This exact photo has already been used for another registration. If you\'re retrying for the same person, check whether they\'re already registered before resubmitting — otherwise, please retake the photo.');
                    }

                    throw $e;
                }
            });
        } catch (\Illuminate\Contracts\Cache\LockTimeoutException $e) {
            throw new \RuntimeException('This photo is still being processed from another submission. Please wait a moment and try again.');
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Rekognition failed: '.$e->getMessage());
            throw new \RuntimeException('We were unable to process your avatar. Please retake your photo and try again.');
        }
    }

    private function generateCode(){
        $count = Participant::count();

        do {
            $code = 'DOSTIX-'.date('m').date('Y').'-R9-'.str_pad(($count+1), 5, '0', STR_PAD_LEFT).'-'.Str::upper(Str::random(6));
        } while (Participant::where('code', $code)->exists());

        return $code;
    }
}
