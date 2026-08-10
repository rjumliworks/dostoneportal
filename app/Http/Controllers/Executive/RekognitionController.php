<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\ParticipantDetail;
use Illuminate\Http\Request;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class RekognitionController extends Controller
{   
    public function test(){
        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region'      => config('services.rekognition.region'),
                'credentials' => [
                    'key'    => config('services.rekognition.key'),
                    'secret' => config('services.rekognition.secret'),
                ],
        ]);
        try {
            $result = $rekognition->listCollections();
            $resultArray = $result->toArray();

            return response()->json($resultArray);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function check(){
        $sts = new \Aws\Sts\StsClient([
            'version' => 'latest',
            'region' => 'ap-southeast-1',
            'credentials' => [
                'key' => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        $identity = $sts->getCallerIdentity();
        dd($identity);
    }

    public function create(){
        try {
            $rekognition = new RekognitionClient([
                'version' => 'latest',
                'region' => 'ap-southeast-1',
                'credentials' => [
                    'key' => config('services.rekognition.key'),
                    'secret' => config('services.rekognition.secret'),
                ],
            ]);

            $result = $rekognition->createCollection([
                'CollectionId' => 'dost9-vip',
            ]);

            return response()->json([
                'message' => 'Collection created successfully!',
                'result' => $result,
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            return response()->json([
                'error' => $e->getAwsErrorMessage(),
                'type'  => $e->getAwsErrorCode(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(){
        try {
            $rekognition = new RekognitionClient([
                'version' => 'latest',
                'region' => 'ap-southeast-1',
                'credentials' => [
                    'key' => config('services.rekognition.key'),
                    'secret' => config('services.rekognition.secret'),
                ],
            ]);

            $collectionId = 'dost9-users';

            // 1. Get all faces
            $faces = $rekognition->listFaces([
                'CollectionId' => $collectionId,
                'MaxResults' => 1000,
            ]);

            if (!empty($faces['Faces'])) {
                $faceIds = array_column($faces['Faces'], 'FaceId');

                // 2. Delete all faces
                $rekognition->deleteFaces([
                    'CollectionId' => $collectionId,
                    'FaceIds' => $faceIds,
                ]);
            }

            return response()->json([
                'message' => 'All faces deleted successfully!',
            ]);

        } catch (\Aws\Exception\AwsException $e) {
            return response()->json([
                'error' => $e->getAwsErrorMessage(),
                'type'  => $e->getAwsErrorCode(),
            ], 500);
        }
    }

    public function search(){
        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => 'ap-southeast-1',
            'credentials' => [
                'key' => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        $result = $rekognition->searchFacesByImage([
            'CollectionId' => 'dost9-users',
            'Image' => [
                'S3Object' => [
                    'Bucket' => config('filesystems.disks.s3.bucket'),
                    'Name'   => 'oneportal/reference/692e69d3d9548.jpg',
                ],
            ],
            'FaceMatchThreshold' => 90,
            'MaxFaces' => 1,
        ]);

        return $result;
    }

    public function deleteCollection(string $collectionId): JsonResponse
    {
        $rekognition = new RekognitionClient([
            'region'  => 'ap-southeast-1',
            'version' => 'latest',
            'credentials' => [
                'key' => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        try {
            $result = $rekognition->deleteCollection([
                'CollectionId' => $collectionId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekognition collection deleted successfully',
                'status'  => $result['StatusCode'],
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getAwsErrorMessage(),
            ], 500);
        }
    }

    public function listFaces(string $collectionId): JsonResponse
    {
        $rekognition = new RekognitionClient([
            'region'  => 'ap-southeast-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        try {
            $faces = [];
            $nextToken = null;

            do {
                $result = $rekognition->listFaces([
                    'CollectionId' => $collectionId,
                    'NextToken'    => $nextToken,
                    'MaxResults'   => 100, // AWS limit
                ]);

                foreach ($result['Faces'] as $face) {
                    $faces[] = [
                        'face_id'           => $face['FaceId'],
                        'image_id'          => $face['ImageId'] ?? null,
                        'external_image_id' => $face['ExternalImageId'] ?? null,
                        'confidence'        => $face['Confidence'] ?? null,
                        'created_at'        => $face['CreatedTimestamp'] ?? null,
                    ];
                }

                $nextToken = $result['NextToken'] ?? null;

            } while ($nextToken);

            // external_image_id is the participant id set at IndexFaces time
            // (see RegistrationController::indexFace) — more than one face_id
            // under the same external_image_id means that participant has
            // duplicate/stale face records instead of the single active one
            // indexFace() is supposed to leave behind after deleting old ones.
            $byParticipant = collect($faces)
                ->filter(fn ($f) => $f['external_image_id'] !== null)
                ->groupBy('external_image_id')
                ->filter(fn ($group) => $group->count() > 1)
                ->map(fn ($group) => $group->values());

            // image_id is assigned per source image at IndexFaces time — more
            // than one face_id sharing the same image_id means that single
            // photo had more than one face detected in it.
            $byImage = collect($faces)
                ->filter(fn ($f) => $f['image_id'] !== null)
                ->groupBy('image_id')
                ->filter(fn ($group) => $group->count() > 1)
                ->map(fn ($group) => $group->values());

            return response()->json([
                'success'    => true,
                'count'      => count($faces),
                'faces'      => $faces,
                'duplicates' => [
                    'by_participant' => $byParticipant,
                    'by_image'       => $byImage,
                ],
            ]);

        } catch (\Aws\Exception\AwsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getAwsErrorMessage(),
            ], 500);
        }
    }

    /**
     * Faces whose external_image_id doesn't match any current participant.
     * These are leftovers from a registration attempt that failed/rolled back
     * after IndexFaces() had already committed the face on the AWS side (no
     * DB transaction covers that call) — they permanently block anyone with a
     * similar-looking photo with "already registered to another participant",
     * for a participant that doesn't actually exist.
     */
    private function fetchOrphanFaces(RekognitionClient $rekognition, string $collectionId): array
    {
        $faces = [];
        $nextToken = null;

        do {
            $result = $rekognition->listFaces([
                'CollectionId' => $collectionId,
                'NextToken'    => $nextToken,
                'MaxResults'   => 100,
            ]);

            foreach ($result['Faces'] as $face) {
                $faces[] = [
                    'face_id'           => $face['FaceId'],
                    'image_id'          => $face['ImageId'] ?? null,
                    'external_image_id' => $face['ExternalImageId'] ?? null,
                    'confidence'        => $face['Confidence'] ?? null,
                    'created_at'        => $face['CreatedTimestamp'] ?? null,
                ];
            }

            $nextToken = $result['NextToken'] ?? null;

        } while ($nextToken);

        $existingIds = Participant::pluck('id')->map(fn ($id) => (string) $id)->all();

        return collect($faces)
            ->filter(fn ($f) => $f['external_image_id'] !== null && !in_array($f['external_image_id'], $existingIds, true))
            ->values()
            ->all();
    }

    public function orphanFaces(string $collectionId): JsonResponse
    {
        $rekognition = new RekognitionClient([
            'region'  => 'ap-southeast-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        try {
            $orphans = $this->fetchOrphanFaces($rekognition, $collectionId);

            return response()->json([
                'success' => true,
                'count'   => count($orphans),
                'faces'   => $orphans,
            ]);

        } catch (\Aws\Exception\AwsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getAwsErrorMessage(),
            ], 500);
        }
    }

    /**
     * Deletes every orphaned face found by fetchOrphanFaces() in a single
     * AWS call, instead of walking deleteFace() one FaceId at a time.
     */
    public function deleteOrphanFaces(string $collectionId): JsonResponse
    {
        $rekognition = new RekognitionClient([
            'region'  => 'ap-southeast-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        try {
            $faceIds = array_column($this->fetchOrphanFaces($rekognition, $collectionId), 'face_id');

            if (empty($faceIds)) {
                return response()->json([
                    'success' => true,
                    'deleted' => 0,
                    'message' => 'No orphaned faces found.',
                ]);
            }

            // deleteFaces() accepts up to 4096 FaceIds per call — comfortably
            // above anything this collection will accumulate as orphans.
            $rekognition->deleteFaces([
                'CollectionId' => $collectionId,
                'FaceIds' => $faceIds,
            ]);

            return response()->json([
                'success'  => true,
                'deleted'  => count($faceIds),
                'face_ids' => $faceIds,
            ]);

        } catch (\Aws\Exception\AwsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getAwsErrorMessage(),
            ], 500);
        }
    }

    public function deleteFace(string $collectionId,string $faceId): JsonResponse
    {
        $rekognition = new RekognitionClient([
            'region'  => 'ap-southeast-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        try {
            $result = $rekognition->deleteFaces([
                'CollectionId' => $collectionId,
                'FaceIds'      => [$faceId], // 👈 single face
            ]);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Face successfully deleted!',
                'info' => "Your file has been deleted and is now available."
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getAwsErrorMessage(),
            ], 500);
        }
    }

    /**
     * Avatar files under oneportal/participants on S3 that no
     * participant_details row references. These are leftovers from
     * registration attempts that uploaded the file (see
     * RegistrationController::uploadAvatar) but failed/rolled back before
     * saving the row that would reference it — DB::transaction() can't undo
     * an S3 write, so nothing else ever cleans these up.
     */
    private function fetchOrphanAvatars(): array
    {
        $files = Storage::disk('s3')->files('oneportal/participants');

        // toBase() bypasses ParticipantDetail::getAvatarAttribute(), which
        // rewrites the raw stored key into a full, cache-busted S3 URL for
        // display — comparing against that instead of the raw key would
        // never match anything and flag every real avatar as orphaned.
        $referenced = ParticipantDetail::whereNotNull('avatar')->toBase()->pluck('avatar')->all();

        return array_values(array_diff($files, $referenced));
    }

    public function orphanAvatars(): JsonResponse
    {
        $orphans = $this->fetchOrphanAvatars();

        return response()->json([
            'success' => true,
            'count'   => count($orphans),
            'files'   => $orphans,
        ]);
    }

    public function deleteOrphanAvatars(): JsonResponse
    {
        $orphans = $this->fetchOrphanAvatars();

        if (empty($orphans)) {
            return response()->json([
                'success' => true,
                'deleted' => 0,
                'message' => 'No orphaned avatars found.',
            ]);
        }

        Storage::disk('s3')->delete($orphans);

        return response()->json([
            'success' => true,
            'deleted' => count($orphans),
            'files'   => $orphans,
        ]);
    }
}
