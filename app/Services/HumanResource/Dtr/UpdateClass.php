<?php

namespace App\Services\HumanResource\Dtr;

use Carbon\Carbon;
use App\Models\Dtr;
use App\Http\Resources\HumanResource\Dtr\IndexResource;

class UpdateClass
{
    public function add($request){
        $tardiness = 0;
        $undertime = 0;

        $dtr = Dtr::where('id',$request->id)->first();

        if($request->type == 'Time Out (pm)'){
            $timeIn = json_decode($dtr->am_in_at, true);
            $minutes = $this->computeLateMinutes($dtr->date,$request->type,$request->time,$timeIn['time']);
            $undertime = $minutes;
        }else{
            $minutes = $this->computeLateMinutes($dtr->date,$request->type,$request->time);
            if($request->type == 'Time In (am)' || $request->type == 'Time In (pm)'){
                $tardiness = $minutes;
            }else{
                $undertime = $minutes;
            }
        }

        $info = [
            'ip' => \Request::ip(), 
            'pcname' => gethostname(),
            'browser' => $request->header('User-Agent'),
            'time' =>  $request->time,
            'minutes' => $minutes,
            'date' => $dtr->date,
            'is_updated' => true,
            'changes' => [
                \Auth::user()->profile->firstname.' '.\Auth::user()->profile->lastname." added the time that is blank to your DTR: {$request->time} with the following note : {$request->remarks}."
            ]
        ];

        if($dtr){
            $status = 'Success';
            switch($request->type){
                case 'Time In (am)':
                    $dtr->am_in_at = json_encode($info);
                break;
                case 'Time Out (am)':
                    $dtr->am_out_at = json_encode($info);
                break;
                case 'Time In (pm)':
                    $dtr->pm_in_at = json_encode($info);
                break;
                case 'Time Out (pm)':
                    $dtr->pm_out_at = json_encode($info);
                break;
            }
            if($dtr->save()){
                $dtr = Dtr::where('id',$request->id)->first();
                $dtr->tardiness += $tardiness;
                $dtr->undertime += $undertime;
                $dtr->save();
                if ($dtr->am_in_at && $dtr->am_out_at && $dtr->pm_in_at && $dtr->pm_out_at) {
                    $dtr->is_completed = 1;
                    $dtr->save();
                }
            }
        }

        $data =  new IndexResource(Dtr::with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname')->where('id',$request->id)->first());
        return [
            'data' => $data,
            'message' => 'DTR Updated successfully.', 
            'info' => 'Your dtr was updated already.',
        ];
    }

    public function save($request){
        $new_tardiness = 0;
        $new_undertime = 0;
        $undertime = 0;
        $tardiness = 0;

        $data = Dtr::where('id',$request->id)->first();
        $old_tardiness = $data->tardiness; 
        $old_undertime = $data->undertime; 
        switch($request->type){
            case 'Time In (am)':
                $column = 'am_in_at';
            break;
            case 'Time Out (am)':
                $column = 'am_out_at';
            break;
            case 'Time In (pm)':
                $column = 'pm_in_at';
            break;
            case 'Time Out (pm)':
                $column = 'pm_out_at';
            break;
        }
        $timeData = json_decode($data->$column, true);
        $toTime = Carbon::parse($request->to_time)->format('H:i:s');
        $timeData['time'] = $toTime;

        if($request->type == 'Time In (am)' || $request->type == 'Time In (pm)'){
            $tardiness = $timeData['minutes'];
        }else if($request->type == 'Time Out (am)' || $request->type == 'Time Out (pm)'){
            $undertime = $timeData['minutes'];
        }

        if($request->type == 'Time Out (pm)'){
            $timeIn = json_decode($data->am_in_at, true);
            $timeData['minutes'] = $this->computeLateMinutes($data->date,$request->type,$request->to_time,$timeIn['time']);
            $new_undertime = $timeData['minutes'];
        }else{
            $timeData['minutes'] = $this->computeLateMinutes($data->date,$request->type,$request->to_time);
            if($request->type == 'Time In (am)' || $request->type == 'Time In (pm)'){
                $new_tardiness = $timeData['minutes'];
            }else{
                $new_undertime = $timeData['minutes'];
            }
        }
        
        $timeData['changes'][] = 
        \Auth::user()->profile->firstname.' '.\Auth::user()->profile->lastname." updated the time from {$request->from_time} to ".Carbon::parse($request->to_time)->format('h:i A')." with the following note : {$request->remarks}.";
        $timeData['is_updated'] = true;
        $update = $data->update([
            $column => json_encode($timeData),
            'tardiness' => ($old_tardiness - $tardiness) + $new_tardiness,
            'undertime' => ($old_undertime - $undertime) + $new_undertime,
            'is_updated' => 1
        ]);
        if($update){
            $dtr = Dtr::where('id',$request->id)->first();
            if ($dtr->am_in_at && $dtr->am_out_at && $dtr->pm_in_at && $dtr->pm_out_at) {
                $dtr->is_completed = 1;
                $dtr->save();
            }
        }
        $data =  new IndexResource(Dtr::with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id')
        ->where('id',$request->id)->first());

        return [
            'data' => $data,
            'message' => 'DTR Updated successfully.', 
            'info' => 'Your dtr was updated already.',
        ];
    }

    public function computeLateMinutes($date,$type,$time,$in = null)
    {
        $date = Carbon::parse($date);
        $time = Carbon::createFromTimeString($time); 
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
                $officialAfternoonTimeIn = Carbon::createFromTimeString('13:00:00');
                $minutes = ($time->greaterThan($officialAfternoonTimeIn)) ? (int) $officialAfternoonTimeIn->diffInMinutes($time) : 0;
            break;
            case 'Time Out (pm)':
                $officialStart = Carbon::createFromTimeString('08:00:00');
                $officialAfternoonOut = Carbon::createFromTimeString('17:00:00');

                if (!$date->isMonday() && $in !== null) {
                    if (strlen($in) === 5) {
                        $timeIn = Carbon::createFromFormat('H:i', $in);
                    } elseif (strlen($in) === 8) {
                        $timeIn = Carbon::createFromFormat('H:i:s', $in);
                    }

                    if ($timeIn->between(
                        Carbon::createFromTimeString('08:00:00'),
                        Carbon::createFromTimeString('08:30:59')
                    )) {
                        $flexMinutes = $officialStart->diffInMinutes($timeIn);
                        $officialAfternoonOut->addMinutes($flexMinutes);
                    }
                }

                $actualOut = $time->copy()->setSeconds(0);
                $adjustedOut = $officialAfternoonOut->copy()->setSeconds(0);

                $minutes = ($actualOut->lessThan($adjustedOut))
                ? $actualOut->diffInMinutes($adjustedOut)
                : 0;
            break;
        }
        return $minutes;
    }
}
