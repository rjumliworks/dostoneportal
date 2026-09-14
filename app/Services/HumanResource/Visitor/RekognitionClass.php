<?php

namespace App\Services\HumanResource\Visitor;

use Hashids\Hashids;
use App\Models\Visitor;
use App\Models\VisitorFace;
use Illuminate\Support\Facades\Storage;
use Aws\Rekognition\RekognitionClient;
use App\Http\Resources\DefaultResource;

class RekognitionClass
{
    public function fetch($request)
    {
        $id = Visitor::where('username', $request->code)->value('id');

        $data = VisitorFace::where('visitor_id', $id)->get();

        // Add signed URL to each file
        $data->transform(function ($file) {
            $file->signed_url = Storage::disk('s3')->temporaryUrl(
                $file->path,            // must be relative key, e.g. "oneportal/69154a7b3fffd.png"
                now()->addMinutes(5)    // expiration
            );
            return $file; // important!
        });

        return DefaultResource::collection($data);
    }

    public function store($request){
        $file = $request->file('file');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $s3Path = $file->storeAs('oneportal/visitors', $filename, 's3');

        $id = Visitor::where('username',$request->code)->value('id');

        try {
            $rekognition = new RekognitionClient([
                'version' => 'latest',
                'region'      => config('services.rekognition.region'),
                'credentials' => [
                    'key'    => config('services.rekognition.key'),
                    'secret' => config('services.rekognition.secret'),
                ],
            ]);

            $result = $rekognition->indexFaces([
                'CollectionId' => config('services.rekognition.collection_id'),
                'Image' => [
                    'S3Object' => [
                        'Bucket' => config('services.rekognition.bucket'),
                        'Name' => $s3Path,
                    ],
                ],
                'ExternalImageId' => (string) $request->code,
                'DetectionAttributes' => ['DEFAULT'],
            ]);
            foreach ($result['FaceRecords'] as $record) {
                VisitorFace::create([
                    'visitor_id' => $id,
                    'face_id' => $record['Face']['FaceId'],
                    'image_id' => $record['Face']['ImageId'],
                    'path' => $s3Path,
                    'name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Rekognition failed: '.$e->getMessage());
        }

        return [
            'data' => true,
            'message' => 'File uploaded successfully!',
            'info' => "Your file has been uploaded and is now available."
        ];
    }

    public function delete($request){
        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region'      => config('services.rekognition.region'),
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);
        $face = VisitorFace::where('id', $request->file_id)->first();

        if ($face && Storage::disk('s3')->exists($face->path)) {
            Storage::disk('s3')->delete($face->path);
            $rekognition->deleteFaces([
                'CollectionId' => config('services.rekognition.collection_id'),
                'FaceIds' => [$face->face_id],
            ]);
            $face->delete();
        }

        return [
            'data' => [],
            'message' => 'File deleted successfully!',
            'info' => "Your file has been deleted and is now available."
        ];
    }

    public function deleteAll($request){
        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region'      => config('services.rekognition.region'),
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        $id = Visitor::where('username', $request->code)->value('id');
        $faces = VisitorFace::where('visitor_id', $id)->get();

        foreach ($faces as $face) {
            if (Storage::disk('s3')->exists($face->path)) {
                Storage::disk('s3')->delete($face->path);
            }
            $rekognition->deleteFaces([
                'CollectionId' => config('services.rekognition.collection_id'),
                'FaceIds' => [$face->face_id],
            ]);
            $face->delete();
        }

        return [
            'data' => [],
            'message' => 'All files deleted successfully!',
            'info' => "All uploaded reference images have been deleted."
        ];
    }
}
