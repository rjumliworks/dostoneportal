<?php

namespace App\Http\Controllers\Event;

use Carbon\Carbon;
use App\Models\Participant;
use App\Services\DropdownClass;
use App\Models\EventSessionParticipant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DefaultResource;

class PublicController extends Controller
{
    protected DropdownClass $dropdown;

    public function __construct(DropdownClass $dropdown){
        $this->dropdown = $dropdown;
    }

    public function index(){
        return inertia('Public/Events/Landing',[
            'dropdowns' => [
                'sexs' => $this->dropdown->dropdowns('Sex'),
                'types' => $this->dropdown->dropdowns('Participant Type')
            ],
        ]);
    }

    public function registration(){
        return inertia('Public/Events/Registration',[
            'dropdowns' => [
                'sexs' => $this->dropdown->dropdowns('Sex'),
                'types' => $this->dropdown->dropdowns('Participant Type')
            ],
        ]);
    }

    public function recognize(Request $request){
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
                'CollectionId' => config('services.rekognition.participant_id'),
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
                $user = Participant::with('detail')->find($externalId); // your user table
                $image = 'data:'.$request->file('image')->getMimeType().';base64,'.base64_encode(file_get_contents($request->file('image')->getRealPath()));
                $datetime =  now();
                $attendance = EventSessionParticipant::where('session_id',$request->session_id)->where('participant_id',$user->id)->whereNotNull('attended_at')->exists();
                if($attendance){
                    $data = [
                        'name' => $user->name,
                        'division' => $user->detail->affiliation
                    ];
                    return [
                        'data' => $data,
                        'message' => null, 
                        'info' => 'Duplicate',
                    ];
                }else{
                    $this->image($request,$user,$datetime);

                    $data = [
                        'name' => $user->name,
                        'division' => $user->detail->affiliation,
                        'avatar' => $user->detail->avatar,
                        'capture' => $image,
                        'datetime' => Carbon::parse($datetime)->format('F j, Y g:i A'),
                    ];
                    return [
                        'data' => $data,
                        'message' => null, 
                        'info' => 'Success',
                    ];
                }
                // return (new DefaultResource($user))->additional(['captured_image' => $image, 'datetime' => $datetime]);
            } else {
                return response()->json(['message' => 'No match found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function image($request,$user,$datetime)
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
        $path = 'images/participants/'.$user->code.'/'.$filename;

        Storage::disk('public')->putFileAs('images/participants/'.$user->code, $file, $filename);
        EventSessionParticipant::where('session_id',$request->session_id)->where('participant_id',$user->id)
        ->update([
            'attended_at' => $datetime,
            'image' =>  $path ,
            'status_id' => 53
        ]);
        return $path;
    }
}
