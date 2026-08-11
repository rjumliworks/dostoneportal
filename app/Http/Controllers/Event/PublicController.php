<?php

namespace App\Http\Controllers\Event;

use Hashids\Hashids;
use Carbon\Carbon;
use App\Events\SessionEvent;
use App\Events\CapacityEvent;
use App\Models\Participant;
use App\Models\EventSession;
use App\Services\DropdownClass;
use App\Models\EventSessionParticipant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ListName;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\Api\Events\Session\ParticipantResource;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class PublicController extends Controller
{
    protected DropdownClass $dropdown;

    public function __construct(DropdownClass $dropdown){
        $this->dropdown = $dropdown;
    }

    public function opening(){
        return inertia('Public/Events/Opening');
    }

    public function qrcode(){
        $result = (new Builder(
            writer: new PngWriter(),
            data: config('app.registration_url') . '/registration',
            size: 800,
            margin: 10,
            logoPath: public_path('images/qrlogo.png'),
            logoResizeToWidth: 100
        ))->build();

        return response($result->getString())->header('Content-Type', $result->getMimeType());
    }

    public function search(Request $request){
        $keyword = $request->keyword;
        $type = $request->type;

        $data = ListName::where('name', 'LIKE', "%{$keyword}%")
        ->where('type',$type)
        ->where('is_active',1)
        ->orderBy('name')
        ->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
            ];
        });
        return $data;
    }


    public function index(){
        return inertia('Public/Events/Landing',[
            'dropdowns' => [
                'sexs' => $this->dropdown->datas('Sex'),
                'types' => $this->dropdown->datas('Participant Type'),
            ],
        ]);
    }

    public function registration(){
        return inertia('Public/Events/Registration',[
            'session' => null,
            'dropdowns' => [
                'suffixes' => $this->dropdown->datas('Suffix'),
                'sexs' => $this->dropdown->datas('Sex'),
                'types' => $this->dropdown->datas('Participant Type'),
            ],
        ]);
    }

    public function register($key){
        try {
            $decryptedKey = Crypt::decryptString($key);
        } catch (DecryptException $e) {
            try {
                $decryptedKey = Crypt::decryptString(urldecode($key));
            } catch (DecryptException $e) {
                abort(404); // or handle invalid/tampered keys
            }
        }

        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($decryptedKey);

        return inertia('Public/Events/Registration',[
            'session' => EventSession::with('venue','schedules','detail')
                ->withCount(['participants' => function ($q) {
                    $q->whereNotIn('status_id', EventSessionParticipant::CAPACITY_EXCLUDED_STATUSES);
                }])
                ->where('id',$id)->first(),
            'dropdowns' => [
                'suffixes' => $this->dropdown->datas('Suffix'),
                'sexs' => $this->dropdown->datas('Sex'),
                'types' => $this->dropdown->datas('Participant Type'),
            ],
        ]);
    }

    public function registervip($key){
        try {
            $decryptedKey = Crypt::decryptString($key);
        } catch (DecryptException $e) {
            try {
                $decryptedKey = Crypt::decryptString(urldecode($key));
            } catch (DecryptException $e) {
                abort(404); // or handle invalid/tampered keys
            }
        }

        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($decryptedKey);

        return inertia('Public/Events/RegistrationVips',[
            'session' => EventSession::with('venue','schedules','detail')
                ->withCount(['participants' => function ($q) {
                    $q->whereNotIn('status_id', EventSessionParticipant::CAPACITY_EXCLUDED_STATUSES);
                }])
                ->where('id',$id)->first(),
            'dropdowns' => [
                'suffixes' => $this->dropdown->datas('Suffix'),
                'sexs' => $this->dropdown->datas('Sex'),
                'types' => $this->dropdown->datas('Participant Type'),
            ],
        ]);
    }


    public function success($key){
        try {
            $decryptedKey = Crypt::decryptString($key);
        } catch (DecryptException $e) {
            try {
                $decryptedKey = Crypt::decryptString(urldecode($key));
            } catch (DecryptException $e) {
                abort(404);
            }
        }

        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($decryptedKey);

        return inertia('Public/Events/Success',[
            'session' => EventSession::with('venue','schedules')->where('id',$id)->first(),
            'isVip' => request()->routeIs('rstw2026.successvip'),
        ]);
    }

    public function successGeneral(){
        return inertia('Public/Events/Success',[
            'session' => null,
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

                $isWalkIn = false;
                $isRegistered = EventSessionParticipant::where('session_id',$request->session_id)->where('participant_id',$user->id)->exists();
                if (!$isRegistered) {
                    $isWalkIn = true;
                    // Walk-in check-in: the face matched a known participant, just not one
                    // pre-registered for this specific session, so register them on the
                    // spot instead of turning them away — the scan itself is proof they're
                    // physically present.
                    EventSessionParticipant::create([
                        'participant_id' => $user->id,
                        'session_id' => $request->session_id,
                        'status_id' => 52, // Pending — flipped to Present (53) by image() below
                        'is_approved' => 0,
                    ]);

                    // Re-fetch instead of broadcasting the create() result directly: a
                    // freshly-created model holds created_at as an in-memory Carbon
                    // instance, but the status/created_at accessors expect a raw DB
                    // string (they call strtotime() on it) — broadcasting the Carbon
                    // version throws a TypeError that isn't caught below, which was
                    // silently aborting the walk-in check-in after the insert had
                    // already committed.
                    $walkIn = EventSessionParticipant::with('participant.detail')
                        ->where('session_id', $request->session_id)
                        ->where('participant_id', $user->id)
                        ->first();

                    // broadcast(new SessionEvent(new ParticipantResource($walkIn), 'register'));
                    // broadcast(new CapacityEvent(
                    //     EventSessionParticipant::where('session_id', $request->session_id)
                    //         ->whereNotIn('status_id', EventSessionParticipant::CAPACITY_EXCLUDED_STATUSES)
                    //         ->count(),
                    //     $request->session_id
                    // ));
                }

                $attendance = EventSessionParticipant::where('session_id',$request->session_id)->where('participant_id',$user->id)->whereNotNull('attended_at')->exists();
                if($attendance){
                    $data = [
                        'name' => $user->name,
                        'division' => $user->detail->affiliation?->name === 'Others'
                            ? $user->detail->others
                            : $user->detail->affiliation?->name
                    ];
                    return [
                        'data' => $data,
                        'message' => null, 
                        'info' => 'Duplicate',
                    ];
                }else{
                    $this->image($request,$user,$datetime);
                    // $broadcast = EventSessionParticipant::where('session_id',$request->session_id)->where('participant_id',$user->id)->first();
                    // broadcast(new SessionEvent(new ParticipantResource($broadcast),'datetime'));
                    $data = [
                        'name' => $user->name,
                        'affiliation' => $user->detail->affiliation?->name === 'Others'
                            ? $user->detail->others
                            : $user->detail->affiliation?->name,
                        'avatar' => $user->detail->avatar,
                        'capture' => $image,
                        'datetime' => Carbon::parse($datetime)->format('F j, Y g:i A'),
                        'message' => $isWalkIn ? 'Participant is not registered but automatically registered to this session.' : null,
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

        Storage::disk('public')->putFileAs('participants/'.$user->code.'/attendance/', $file, $filename);
        EventSessionParticipant::where('session_id',$request->session_id)->where('participant_id',$user->id)
        ->update([
            'attended_at' => $datetime,
            'image' =>  $path ,
            'status_id' => 53
        ]);
        return $path;
    }

    
}
