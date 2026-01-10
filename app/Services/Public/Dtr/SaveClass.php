<?php

namespace App\Services\Public\Dtr;

use Carbon\Carbon;
use App\Models\Dtr;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SaveClass
{
    public function recognize($request){
        $type = $request->type;
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
                'CollectionId' => config('services.rekognition.collection_id'),
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
                $user = User::find($externalId); // your user table
                $request['type'] = $type;
                $request['username'] = $user->username;
                $result = $this->store($request);
                return $result;
            } else {
                return response()->json(['message' => 'No match found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store($request)
    {
        $date = Carbon::now();
        $time = Carbon::now();
        $type = $request->type;

        $cutoff = Carbon::createFromTimeString('12:30:00');
        $type .= ($time->lte($cutoff)) ? ' (am)' : ' (pm)'; 
        $minutes = 0;
        $is_completed = 0;

        switch($type){
            case 'Time In (am)':
                if ($date->isMonday()) {
                    $officialStart = Carbon::createFromTimeString('08:00:00');
                    $officialMorningTimeIn = Carbon::createFromTimeString('8:00:59');
                    $minutes = ($time->greaterThan($officialMorningTimeIn)) ? (int)  $officialStart->diffInMinutes($time) : 0;
                }else{
                    $officialStart = Carbon::createFromTimeString('08:00:00');
                    $flexibleCutoff = Carbon::createFromTimeString('08:30:59');
                    $minutes = ($time->greaterThan($flexibleCutoff)) ? (int) $officialStart->diffInMinutes($time) : 0;
                }
            break;
            case 'Time Out (am)':
                $officialMorningOut = Carbon::createFromTimeString('12:00:00');
                $minutes = ($time->lessThan($officialMorningOut)) ? ceil($time->diffInMinutes($officialMorningOut)) : 0;
            break;
            case 'Time In (pm)':
                $officialAfternoonTimeIn = Carbon::createFromTimeString('13:00:59'); //59 seconds
                if ($time->lessThanOrEqualTo($officialAfternoonTimeIn)) {
                    $minutes = 0;
                }else{
                    // $minutes = ($time->greaterThan($officialAfternoonTimeIn)) ? (int) $officialAfternoonTimeIn->diffInMinutes($time) : 0;
                    $secondsLate = $officialAfternoonTimeIn->diffInSeconds($time);
                    $minutes = (int) ceil($secondsLate / 60);
                }
            break;
            case 'Time Out (pm)':
                $officialAfternoonOut = Carbon::createFromTimeString('17:00:00');
                $minutes = ($time->lessThan($officialAfternoonOut)) ? ceil($time->floatDiffInMinutes($officialAfternoonOut)) : 0;
            break;
        }

        $info = [
            'ip' => \Request::ip(), 
            'pcname' => gethostname(),
            'browser' => $request->header('User-Agent'),
            'time' =>  $time->toTimeString(),
            'date' => $date,
            'minutes' => $minutes,
            'image' => $this->image($request),
            'is_updated' => false,
            'changes' => []
        ];

        $user = User::with('profile','organization.division')->where('username',$request->username)->first();
        if($user){
            $dtr = Dtr::whereDate('date',$date)->where('user_id',$user->id)->first();
            $status = null;
            $remarks = [
                'tardiness' => null,
                'undertime' => null
            ]; 
            if($dtr){
                switch($type){
                    case 'Time In (am)':
                        if($dtr->am_out_at) {
                            $status = 'Disabled Overlap';
                            break;
                        }
                        if($date->hour >= 12) {
                            $status = 'Disabled AM';
                            break;
                        }
                        if($dtr->am_in_at) {
                            $status = 'Duplicate';
                            break;
                        }
                        $status = 'Success';
                        $dtr->tardiness += $minutes;
                        $dtr->am_in_at = json_encode($info);
                        $dtr->save();
                    break;
                    case 'Time Out (am)':
                        if ($dtr->am_out_at) {
                            $status = 'Duplicate';
                            break;
                        }

                        $status = 'Success';
                        $dtr->undertime += $minutes;
                        $dtr->am_out_at = json_encode($info);
                        $dtr->save();
                    break;
                    case 'Time In (pm)':
                        if (!empty(json_decode($dtr->pm_out_at))) {
                            $status = "Disabled Overlap";
                        }else if($date->hour >= 17) {
                            $status = "Disabled";
                        }else if($dtr->pm_in_at){
                            $status = 'Duplicate';
                        }else{
                            $status = 'Success';
                            $dtr->tardiness += $minutes;
                            $dtr->pm_in_at = json_encode($info);
                            $dtr->save();
                        }
                    break;
                    case 'Time Out (pm)':
                        if(!empty(json_decode($dtr->pm_out_at))){
                            $status = 'Duplicate';
                        }else{
                            $status = 'Success';
                            $dtr->undertime += $minutes;
                            $dtr->pm_out_at = json_encode($info);
                            $dtr->save();
                        }
                    break;
                }
            }else{
                $dtr = new Dtr;
                $dtr->date = Carbon::today();
                $dtr->am_in_at = ($type == 'Time In (am)') ? json_encode($info) : null;
                $dtr->am_out_at = ($type == 'Time Out (am)') ? json_encode($info) : null;
                $dtr->pm_in_at = ($type == 'Time In (pm)') ? json_encode($info) : null;
                $dtr->pm_out_at = ($type == 'Time Out (pm)') ? json_encode($info) : null;
                $dtr->tardiness += $minutes;
                $dtr->remarks = json_encode($remarks);
                $dtr->user_id = $user->id;
                if($dtr->save()){
                    $status = 'New';
                }
            }

            $name = $user->profile->firstname.' '.$user->profile->lastname;
            $data = [
                'username' => $user->username,
                'name' => $name,
                'division' => $user->organization->division->name,
                'avatar' => ($user->profile->avatar === 'avatar.jpg') ? '/images/avatars/'.$user->profile->avatar : '/storage/'.$user->profile->avatar,
                'time' => \Carbon\Carbon::parse($time)->format('g:i A'),
                'type' => $type,
                'status' => $status,
            ];
            return [
                'data' => $data,
                'message' => null, 
                'info' => $status,
            ];
        }else{
            return [
                'data' => null,
                'message' => null, 
                'info' => 'Error',
            ];
        }
    }

    public function image($request)
    {
        $image = $request->input('image'); // base64 string

        // Validate format
        if (!preg_match('/^data:image\/(\w+);base64,/', $image, $matches)) {
            return response()->json(['error' => 'Invalid image format.'], 422);
        }

        $type = strtolower($matches[1]); // png, jpg, jpeg, gif
        if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
            return response()->json(['error' => 'Invalid image type.'], 422);
        }

        // Remove header and decode
        $image = substr($image, strpos($image, ',') + 1);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        if ($imageData === false) {
            return response()->json(['error' => 'Base64 decode failed.'], 422);
        }

        // Save to storage/app/public/images/attendance
        $filename = Str::random(10) . '.' . $type;
        $path = 'images/attendance/'.$request->username.'/'. $filename;
        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

}
