<?php

namespace App\Services\HumanResource\Dtr;

use Carbon\Carbon;
use App\Models\Dtr;
use Illuminate\Support\Facades\Artisan;
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
                if($dtr->save()){
                    if ($dtr->am_in_at && $dtr->am_out_at && $dtr->pm_in_at && $dtr->pm_out_at) {
                        $dtr->is_completed = 1;
                        if($dtr->save()){
                            Artisan::call('dtr', [
                                'id' => $dtr->id,
                            ]);
                        }
                    }
                    
                }
            }
        }

        $data =  new IndexResource(Dtr::with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname','station')->where('id',$request->id)->first());
        return [
            'data' => $data,
            'message' => 'DTR Updated successfully.', 
            'info' => 'Your dtr was updated already.',
        ];
    }

    public function swapType($request){
        $dtr = Dtr::where('id',$request->id)->first();

        if(!$dtr){
            return [
                'data' => null,
                'message' => 'DTR not found.',
                'info' => 'DTR not found.',
                'status' => false,
            ];
        }

        $fromColumn = $this->columnForType($request->from_type);
        $toColumn = $this->columnForType($request->to_type);

        if(!$fromColumn || !$toColumn){
            return [
                'data' => null,
                'message' => 'Invalid time slot.',
                'info' => 'Please select a valid time slot.',
                'status' => false,
            ];
        }

        if($fromColumn === $toColumn){
            return [
                'data' => null,
                'message' => 'Nothing to move.',
                'info' => 'Please select a different time slot to move this record to.',
                'status' => false,
            ];
        }

        $movingRaw = $dtr->$fromColumn;
        if(!$movingRaw){
            return [
                'data' => null,
                'message' => 'Nothing to move.',
                'info' => 'This time slot is empty.',
                'status' => false,
            ];
        }

        $causer = \Auth::user()->profile->firstname.' '.\Auth::user()->profile->lastname;

        $moving = json_decode($movingRaw, true);
        if($request->filled('to_time')){
            $moving['time'] = Carbon::parse($request->to_time)->format('H:i:s');
        }
        $moving['is_updated'] = true;
        $moving['changes'][] = $causer." moved this record (".Carbon::parse($moving['time'])->format('h:i A').") from {$request->from_type} to {$request->to_type}".($request->remarks ? ", with note: {$request->remarks}." : ".");

        $existingInTarget = $dtr->$toColumn ? json_decode($dtr->$toColumn, true) : null;

        $dtr->$toColumn = json_encode($moving);

        if($existingInTarget){
            $existingInTarget['is_updated'] = true;
            $existingInTarget['changes'][] = $causer." swapped this record into {$request->from_type} to make room for the {$request->to_type} record moved in.";
            $dtr->$fromColumn = json_encode($existingInTarget);
        }else{
            $dtr->$fromColumn = null;
        }

        $dtr->save();

        // let the canonical shift-aware recompute (grace periods, flex schedule, holidays, etc.)
        // figure out tardiness/undertime/is_completed for the moved (and possibly swapped) slots
        Artisan::call('dtr', ['id' => $dtr->id]);

        $data = new IndexResource(Dtr::with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname','station')->where('id',$request->id)->first());

        return [
            'data' => $data,
            'message' => $existingInTarget ? 'DTR records swapped successfully.' : 'DTR record moved successfully.',
            'info' => 'The time record has been transferred to the new slot.',
        ];
    }

    private function columnForType($type)
    {
        return match($type){
            'Time In (am)' => 'am_in_at',
            'Time Out (am)' => 'am_out_at',
            'Time In (pm)' => 'pm_in_at',
            'Time Out (pm)' => 'pm_out_at',
            default => null,
        };
    }

    public function save($request){
        $new_tardiness = 0;
        $new_undertime = 0;
        $undertime = 0;
        $tardiness = 0;

        $data = Dtr::where('id',$request->id)->first();
        $old_tardiness = $data->tardiness;
        $old_undertime = $data->undertime;
        $column = $this->columnForType($request->type);
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

    public function bulkRecheck($request){
        $query = Dtr::query();

        if($request->from && $request->to){
            $query->whereBetween('date', [$request->from, $request->to]);
        }elseif($request->month && $request->year){
            $query->whereMonth('date', $request->month)->whereYear('date', $request->year);
        }else{
            return [
                'data' => null,
                'message' => 'Error occured',
                'info' => 'Please select a date range or a month/year to fix.',
                'status' => false,
            ];
        }

        $query->when($request->station, function ($query, $station) {
            $query->where('station_id', $station);
        });

        $ids = $query->pluck('id');

        foreach($ids as $id){
            Artisan::call('dtr', ['id' => $id]);
        }

        $count = $ids->count();

        return [
            'data' => null,
            'message' => 'DTR records fixed successfully.',
            'info' => "Rechecked and fixed {$count} DTR record(s).",
        ];
    }

    public function recheck($request){
        $dtr = Dtr::where('id',$request->id)->first();

        if(!$dtr){
            return [
                'data' => null,
                'message' => 'DTR not found.',
                'info' => 'DTR not found.',
            ];
        }

        Artisan::call('dtr', ['id' => $dtr->id]);

        $data = new IndexResource(Dtr::with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id')
        ->where('id',$request->id)->first());

        return [
            'data' => $data,
            'message' => 'DTR rechecked successfully.',
            'info' => 'Your dtr was rechecked already.',
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
