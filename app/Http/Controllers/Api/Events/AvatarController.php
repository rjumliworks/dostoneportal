<?php

namespace App\Http\Controllers\Api\Events;

use Hashids\Hashids;
use App\Models\Participant;
use App\Models\ParticipantFace;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aws\Rekognition\RekognitionClient;

class AvatarController extends Controller
{
    public function completed(Request $request){
        $data = Participant::where('id',$request->id)->first();
        $data->is_completed = 1;
        $data->save();
        $data->refresh();

        return response()->json([
            'status'  => true,
            'message' => 'Completed updated successfully',
            'data'    => $data->is_completed
        ]);
    }

    public function avatar(Request $request){

        try {
            $request->validate([
                'image' => 'required|image|max:2048', // Assuming maximum file size is 2MB
            ]);
                $file = $request->file('image');
                $participant = Participant::with('detail')->findOrFail($request->id);

                // Delete old avatar if exists
                // if ($participant->detail->avatar) {
                //     Storage::disk('public')->delete($participant->detail->avatar);
                // }
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = strtolower($participant->code). '.' . $extension;
                $s3Path = $file->storeAs('oneportal/participants', $filename, 's3');

                $participant->detail->avatar = $s3Path;
                $participant->detail->save();

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

                    // Re-uploading an avatar must replace the participant's indexed
                    // face, not stack a new one alongside it — otherwise Rekognition
                    // ends up with multiple faces under the same ExternalImageId and
                    // scans can match against a stale photo. Mirrors
                    // RegistrationController::indexFace().
                    $existingFaces = ParticipantFace::where('participant_id', $participant->detail->id)->get();
                    if ($existingFaces->isNotEmpty()) {
                        $rekognition->deleteFaces([
                            'CollectionId' => $collectionId,
                            'FaceIds' => $existingFaces->pluck('face_id')->all(),
                        ]);
                        ParticipantFace::whereIn('id', $existingFaces->pluck('id'))->delete();
                    }

                    $result = $rekognition->indexFaces([
                        'CollectionId' => $collectionId,
                        'Image' => [
                            'S3Object' => [
                                'Bucket' => config('services.rekognition.bucket'),
                                'Name' => $s3Path,
                            ],
                        ],
                        'ExternalImageId' => (string) $participant->id,
                        'DetectionAttributes' => ['DEFAULT'],
                    ]);
                    foreach ($result['FaceRecords'] as $record) {
                        ParticipantFace::create([
                            'participant_id' => $participant->detail->id,
                            'face_id' => $record['Face']['FaceId'],
                            'image_id' => $record['Face']['ImageId'],
                            'status' => 'active',
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Rekognition failed: '.$e->getMessage());
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Profile updated successfully',
                    'data'    => $participant->detail->avatar
                ]);

        }catch(\Throwable $th){

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function signature(Request $request){

        try {
            $request->validate([
                'signature' => 'required|image|max:2048', // Assuming maximum file size is 2MB
            ]);
           
                $participant = Participant::with('detail')->findOrFail($request->id);

                // Delete old avatar if exists
                if ($participant->detail->signature) {
                    Storage::disk('public')->delete('signatures/' . $participant->detail->signature);
                }

                $hashids = new Hashids('krad',10);
                $key = $hashids->encode($request->id);
                // Store new image
                $extension = $request->file('signature')->getClientOriginalExtension();
                $filename  = $key . '.' . $extension;
                $path = $request->file('signature')->storeAs('images/signatures', $filename, 'public');

                $participant->detail->signature = $path;
                $participant->detail->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Profile updated successfully',
                    'data'    => $this->convertToBase64($participant->detail->signature)
                ]);

        }catch(\Throwable $th){

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    private function convertToBase64($path)
    {
        // If you store public files like: storage/app/public/signatures/filename.png
        // and you saved the DB value like: signatures/filename.png
        if (Storage::disk('public')->exists($path)) {
            $file = Storage::disk('public')->get($path);
            $mime = Storage::disk('public')->mimeType($path);
            return 'data:' . $mime . ';base64,' . base64_encode($file);
        }

        // If you stored a full URL instead of a storage path:
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            try {
                $file = file_get_contents($path);
                $mime = @mime_content_type($path) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode($file);
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }
}
