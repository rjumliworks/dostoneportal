<?php

namespace App\Http\Controllers\Event;

use App\Models\Participant;
use App\Models\ParticipantDetail;
use App\Models\ParticipantFace;
use App\Models\EventSessionParticipant;
use App\Models\ListName;
use App\Jobs\RegistrationJob;
use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\Exception\RekognitionException;
use App\Http\Requests\Event\ParticipantRequest;

class RegistrationController extends Controller
{
    use HandlesTransaction;

    public function store(ParticipantRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            $participant = Participant::create(array_merge($request->except('avatar'), [
                'code' => $this->generateCode()
            ]));

            if ($participant) {
                if($request->session_id){
                    EventSessionParticipant::create([
                        'status_id' => 52,
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

                $name = ucwords(strtolower($request->firstname.' '.$request->lastname));
                RegistrationJob::dispatch($request->email,$name)->onConnection('database');
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

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
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
