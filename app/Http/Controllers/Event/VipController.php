<?php

namespace App\Http\Controllers\Event;

use Carbon\Carbon;
use App\Models\Vip;
use App\Events\VipEvent;
use App\Events\VipSignalEvent;
use Illuminate\Support\Str;
use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\Exception\RekognitionException;

class VipController extends Controller
{
    use HandlesTransaction;

    public function scanner(){
        return inertia('Public/Events/Scanner');
    }

    public function registration(){
        return inertia('Public/Events/VipRegistration');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:191',
            'designation' => 'required|string|max:191',
            'affiliation' => 'required|string|max:191',
            'avatar' => 'required|image',
        ]);

        $result = $this->handleTransaction(function () use ($request) {
            // face_id/image_id are NOT NULL + unique on the vips table but only
            // exist once Rekognition indexes the face below, which itself needs
            // the row's id as ExternalImageId - so create with placeholders,
            // index using the real id, then overwrite them with the real values.
            $vip = Vip::create([
                'name' => $request->name,
                'designation' => $request->designation,
                'affiliation' => $request->affiliation,
                'avatar' => 'avatar.jpg',
                'face_id' => (string) Str::uuid(),
                'image_id' => (string) Str::uuid(),
            ]);

            $avatarPath = $this->uploadAvatar($vip, $request->file('avatar'));
            $vip->avatar = $avatarPath;
            $vip->save();

            $this->indexFace($vip, $avatarPath);

            return [
                'data' => $vip,
                'message' => 'VIP registered successfully.',
                'info' => 'Registration submitted successfully.',
            ];
        });

        if (!$result['status']) {
            $error = preg_replace('/^An unexpected error occurred:\s*/', '', $result['info'] ?? $result['message']);
            return back()->withErrors(['avatar' => $error]);
        }

        return back()->with(['status' => 'registered']);
    }

    private function uploadAvatar(Vip $vip, UploadedFile $file){
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(10).'.'.$extension;
        return $file->storeAs('oneportal/vips', $filename, 's3');
    }

    /**
     * Index the avatar as the VIP's face in Rekognition, mirroring
     * RegistrationController::indexFace() but against the VIP collection and
     * without a separate face-history table - the vips row itself just holds
     * the single current face_id/image_id.
     *
     * Throws on any Rekognition failure, including "no face detected" and
     * "face already belongs to someone else" - the caller (store()) relies on
     * this to roll back the whole registration instead of saving a VIP with
     * no usable face record.
     */
    private function indexFace(Vip $vip, string $s3Path){
        try {
            $rekognition = new RekognitionClient([
                'version'     => 'latest',
                'region'      => config('services.rekognition.region'),
                'credentials' => [
                    'key'    => config('services.rekognition.key'),
                    'secret' => config('services.rekognition.secret'),
                ],
            ]);

            $collectionId = config('services.rekognition.vip_id');
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
                    throw new \RuntimeException('No face was detected in the photo. Please retake with the face clearly visible.');
                }
                throw $e;
            }

            $match = $matches['FaceMatches'][0] ?? null;
            if ($match && ($match['Face']['ExternalImageId'] ?? null) !== (string) $vip->id) {
                throw new \RuntimeException('This face is already registered to another VIP.');
            }

            $result = $rekognition->indexFaces([
                'CollectionId' => $collectionId,
                'Image' => $image,
                'ExternalImageId' => (string) $vip->id,
                'DetectionAttributes' => ['DEFAULT'],
            ]);

            if (empty($result['FaceRecords'])) {
                throw new \RuntimeException('No face was detected in the photo. Please retake with the face clearly visible.');
            }

            $face = $result['FaceRecords'][0]['Face'];
            $vip->face_id = $face['FaceId'];
            $vip->image_id = $face['ImageId'];
            $vip->save();
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Rekognition failed (VIP): '.$e->getMessage());
            throw new \RuntimeException('We were unable to process the photo. Please retake and try again.');
        }
    }

    public function check(Request $request){
        $request->validate(['image' => 'required|image']);

        $file = $request->file('image');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        $s3Path = $file->storeAs('oneportal/temp', $filename, 's3');
        $rekognition = new \Aws\Rekognition\RekognitionClient([
            'version'     => 'latest',
            'region'      => config('services.rekognition.region'),
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        try {
            $matches = $rekognition->searchFacesByImage([
                'CollectionId' => config('services.rekognition.vip_id'),
                'Image' => [
                    'S3Object' => [
                        'Bucket' => config('services.rekognition.bucket'),
                        'Name' => $s3Path,
                    ],
                ],
                'FaceMatchThreshold' => 90,
                'MaxFaces' => 1,
            ]);

            if (!empty($matches['FaceMatches'])) {
                $externalId = $matches['FaceMatches'][0]['Face']['ExternalImageId'];
                $user = Vip::find($externalId);

                if (!$user) {
                    return response()->json(['message' => 'VIP not found'], 404);
                }

                $image = 'data:'.$request->file('image')->getMimeType().';base64,'.base64_encode(file_get_contents($request->file('image')->getRealPath()));
                $datetime = now();

                $data = [
                    'name' => $user->name,
                    'designation' => $user->designation,
                    'affiliation' => $user->affiliation,
                    'avatar' => $user->avatar,
                    'datetime' => Carbon::parse($datetime)->format('F j, Y g:i A'),
                ];

                broadcast(new VipEvent($data));

                return [
                    'data' => $data,
                    'message' => null,
                    'info' => 'Success',
                ];
            } else {
                return response()->json(['message' => 'No match found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Relays WebRTC signaling (SDP offers/answers, ICE candidates) between
     * Scanner.vue and the separate FaceRecognitionPage.jsx display app. No
     * video passes through here or through Reverb - this just lets the two
     * browsers find each other and negotiate a direct peer connection.
     */
    public function signal(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:scanner-ready,join,offer,answer,ice-candidate,leave',
            'from' => 'required|string|in:scanner,display',
            'data' => 'nullable',
        ]);

        broadcast(new VipSignalEvent($request->only(['type', 'from', 'data'])));

        return response()->json(['ok' => true]);
    }

    /**
     * Short-lived TURN credentials for the WebRTC signal above, generated
     * against coturn's shared "static-auth-secret" (the TURN REST API
     * convention coturn itself implements) rather than a fixed username/
     * password baked into either frontend bundle - anyone reading the JS
     * only ever finds a credential that already expired.
     */
    public function turnCredentials()
    {
        $secret = config('services.turn.secret');

        if (!$secret) {
            return response()->json(['error' => 'TURN not configured'], 503);
        }

        $ttl = (int) config('services.turn.ttl', 3600);
        $host = config('services.turn.host');
        $port = config('services.turn.port');

        $username = (now()->timestamp + $ttl).':vip';
        $credential = base64_encode(hash_hmac('sha1', $username, $secret, true));

        return response()->json([
            'ttl' => $ttl,
            'username' => $username,
            'credential' => $credential,
            'urls' => [
                "turn:{$host}:{$port}?transport=udp",
                "turn:{$host}:{$port}?transport=tcp",
            ],
        ]);
    }

     public function image($request,$user)
    {
        
        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return response()->json(['error' => 'Invalid image upload.'], 422);
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
  
        if (!in_array(strtolower($extension), ['jpg','jpeg','png'])) {
            return response()->json(['error' => 'Invalid image type.'], 422);
        }

        $filename = Str::random(10).'.'.$extension;
        $path = 'images/vips/'.$user->id.'/'.$filename;
        return $path;
    }
}
