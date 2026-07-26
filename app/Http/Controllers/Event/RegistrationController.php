<?php

namespace App\Http\Controllers\Event;

use Hashids\Hashids;
use App\Models\Participant;
use App\Models\ParticipantDetail;
use App\Models\ParticipantFace;
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
use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\Exception\RekognitionException;
use App\Http\Requests\Event\ParticipantRequest;
use App\Http\Resources\Api\Events\Session\ParticipantResource;

class RegistrationController extends Controller
{
    use HandlesTransaction;

    public function store(ParticipantRequest $request){
        // Captured by reference: HandlesTransaction::handleTransaction() only
        // passes through data/message/info/status from the callback's return
        // value, so this is how the redirect logic below finds out whether
        // the registration landed as Reserved.
        $isReserved = false;

        $result = $this->handleTransaction(function () use ($request, &$isReserved) {
            $participant = Participant::create(array_merge($request->except('avatar'), [
                'code' => $this->generateCode(),
                'is_completed' => 1
            ]));

            if ($participant) {
                if ($request->session_id) {
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
                        'status_id' => $isReserved ? 60 : 52, // Reserved : Pending
                        'participant_id' => $participant->id,
                        'session_id' => $request->session_id,
                        'is_approved' => 0,
                    ]);
                }
                $detail_data = $request->except('captcha', 'avatar', 'signature');

                if (($detail_data['affiliation_id'] ?? null) === 'others') {
                    $detail_data['affiliation_id'] = ListName::firstOrCreate(
                        ['name' => 'Others', 'type' => 'Affiliation'],
                        ['is_active' => 1]
                    )->id;
                }

                if ($request->hasFile('signature')) {
                    $signature_file = $request->file('signature');
                    $signature_name = $participant->code.'.'.$signature_file->getClientOriginalExtension();
                    $detail_data['signature'] = $signature_file->storeAs('participants/'.$participant->code.'/signature/', $signature_name, 'public');
                }

                $avatar_path = null;
                if ($request->hasFile('avatar')) {
                    $avatar_path = $this->uploadAvatar($participant, $request->file('avatar'));
                    $detail_data['avatar'] = $avatar_path;
                }

                $detail = $participant->detail()->create($detail_data);

                if ($avatar_path) {
                    $this->indexFace($participant, $detail, $avatar_path);
                }
                
                if($request->session_id){
                    $data = EventSessionParticipant::with('participant.detail')
                        ->where('participant_id', $participant->id)
                        ->where('session_id', $request->session_id)
                        ->first();
                    broadcast(new SessionEvent(new ParticipantResource($data),'register'));
                    broadcast(new CapacityEvent(
                        EventSessionParticipant::where('session_id', $request->session_id)
                            ->whereNotIn('status_id', EventSessionParticipant::CAPACITY_EXCLUDED_STATUSES)
                            ->count(),
                        $request->session_id
                    ));
                }

                $name = ucwords(strtolower($request->firstname.' '.$request->lastname));

                RegistrationJob::dispatch($request->email,$name,$request->session_id,$isReserved)->onConnection('database');
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

        return redirect()->route('rstw2026.success', ['key' => $key])->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
            'reserved' => $isReserved,
        ]);
    }

    public function avatar(Request $request){
        try {
            $request->validate([
                'image' => 'required|image|max:2048', // Assuming maximum file size is 2MB
            ]);

            $participant = Participant::with('detail')->findOrFail($request->id);
            $s3Path = $this->uploadAvatar($participant, $request->file('image'));

            $participant->detail->avatar = $s3Path;
            $participant->detail->save();

            $this->indexFace($participant, $participant->detail, $s3Path);

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
     */
    private function indexFace(Participant $participant, ParticipantDetail $detail, string $s3Path){
        try {
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
            if ($match && ($match['Face']['ExternalImageId'] ?? null) !== (string) $participant->id) {
                throw new \RuntimeException('This face is already registered to another participant. Please use your own photo.');
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

            foreach ($result['FaceRecords'] as $record) {
                ParticipantFace::create([
                    'participant_id' => $detail->id,
                    'face_id' => $record['Face']['FaceId'],
                    'image_id' => $record['Face']['ImageId'],
                    'status' => 'active',
                ]);
            }
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Rekognition failed: '.$e->getMessage());
            throw new \RuntimeException('We were unable to process your avatar. Please retake your photo and try again.');
        }
    }

    private function generateCode(){
        $count = Participant::count();
        $code = 'DOSTIX-'.date('m').date('Y').'-R9-'.str_pad(($count+1), 5, '0', STR_PAD_LEFT);  //$tsr_count+ remove since it will reset
        return $code;
    }
}
