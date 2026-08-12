<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\DropdownClass;
use App\Services\Events\Participant\ViewClass;
use App\Models\Participant;
use App\Models\ParticipantDetail;
use App\Models\ParticipantFace;
use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\Exception\RekognitionException;
use Illuminate\Database\UniqueConstraintViolationException;

class ParticipantController extends Controller
{
    protected ViewClass $view;
    protected DropdownClass $dropdown;

    public function __construct(ViewClass $view, DropdownClass $dropdown){
        $this->view = $view;
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        switch($request->option){
            case 'list':
                return $this->view->list($request);
            break;
            case 'show':
                return $this->view->show($request->id);
            break;
            default:
            return inertia('Modules/Events/Participants/Index',[
                'dropdowns' => [
                    'types' => $this->dropdown->datas('Participant'),
                ],
                'counts' => $this->view->counts()
            ]);
        }
    }

    /**
     * Staff-side fallback for when facial recognition fails at check-in:
     * retake the participant's photo on the spot and re-index it as their
     * face, replacing whatever's currently on file. Mirrors
     * RegistrationController::avatar()/indexFace(), the self-service path
     * participants use during registration.
     */
    public function avatar(Request $request, $id){
        try {
            $request->validate([
                'image' => 'required|image|max:2048', // Assuming maximum file size is 2MB
            ]);

            $participant = Participant::with('detail')->findOrFail($id);
            $s3Path = $this->uploadAvatar($participant, $request->file('image'));

            $participant->detail->avatar = $s3Path;
            $participant->detail->save();

            $this->indexFace($participant, $participant->detail, $s3Path);

            return response()->json([
                'status'  => true,
                'message' => 'Photo updated and face re-registered successfully.',
                'data'    => $participant->detail->avatar
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
     * "face already belongs to someone else" — the caller relies on this to
     * surface a clear message to staff instead of silently leaving the
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
                    throw new \RuntimeException('No face was detected in the photo. Please retake it with the participant\'s face clearly visible.');
                }
                throw $e;
            }

            $match = $matches['FaceMatches'][0] ?? null;
            $matchedId = $match['Face']['ExternalImageId'] ?? null;

            if ($match && $matchedId !== (string) $participant->id) {
                if ($matchedId !== null && Participant::where('id', $matchedId)->exists()) {
                    throw new \RuntimeException('This face is already registered to another participant.');
                }

                // Stale/orphaned face: indexed by an earlier attempt that
                // failed/rolled back after IndexFaces() had already committed
                // it on the AWS side. Nothing in the DB owns it, so delete it
                // and continue indexing this participant's photo instead of
                // blocking staff over a ghost record.
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
                throw new \RuntimeException('No face was detected in the photo. Please retake it with the participant\'s face clearly visible.');
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
                // permanently indexed under a participant whose face update is
                // about to be reported as failed, and later blocks anyone with
                // a similar face as "already registered to another participant".
                $rekognition->deleteFaces([
                    'CollectionId' => $collectionId,
                    'FaceIds' => array_column(array_column($result['FaceRecords'], 'Face'), 'FaceId'),
                ]);

                // ImageId is a content hash, not a random id like FaceId — a
                // collision here means this exact photo was already indexed by
                // another submission (e.g. this update was somehow triggered
                // twice). Surface that plainly instead of a raw SQL error.
                if ($e instanceof UniqueConstraintViolationException) {
                    throw new \RuntimeException('This exact photo has already been used for another registration. Please retake the photo and try again.');
                }

                throw $e;
            }
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Rekognition failed: '.$e->getMessage());
            throw new \RuntimeException('We were unable to process this photo. Please retake it and try again.');
        }
    }
}
