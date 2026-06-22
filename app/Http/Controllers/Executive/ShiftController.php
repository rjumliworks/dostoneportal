<?php

namespace App\Http\Controllers\Executive;

use Carbon\Carbon;
use App\Models\Dtr;
use App\Models\UserShift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ShiftController extends Controller
{
     public function index(Request $request){

        $users = UserShift::with('shift.times')->where('user_id',1)->get();
        
        foreach($users as $user){
            $shifts = $user->shift->times;
        
            $dtrs = Dtr::whereMonth('date', 6)->whereYear('date', 2026)->where('user_id',$user->id)->get();
            foreach ($dtrs as $dtr) {
              
                $tardiness = 0;
                $undertime = 0;
                $date = Carbon::parse($dtr->date);
                foreach($shifts as $shift){
                    if($shift->segment == 'am'){
                        if($dtr->am_in_at){
                            $d = json_decode($dtr->am_in_at);
                            // dd($d->time);
                            if($date->isMonday()) {
                                $start_time = $shift->in_time;
                            }else{
                                $time = Carbon::parse($d->time);
                                $officialStart = Carbon::parse($shift->in_time);
                                $flexibleCutoff = Carbon::parse($shift->in_time)->addMinutes($shift->in_grace);
                                $tardiness= ($time->greaterThan($flexibleCutoff)) ? (int) $officialStart->diffInMinutes($time) : 0;
                                // $actualIn = Carbon::parse($d->time);
                                // $officialStart = Carbon::parse($shift->in_time);

                                // $cutoff = Carbon::parse($shift->in_time)
                                // ->addMinutes($shift->in_grace)
                                // ->addSeconds(59);

                                // dd($cutoff);

                                // if ($actualIn->gt($cutoff)) {
                                //     $tardiness += $officialStart->diffInMinutes($actualIn);
                                //     // dd($tardiness);
                                // }

                                // $start_time = $shift->in_time;
                                // $grace_time = $shift->in_grace;

                                // $officialStart = Carbon::parse($shift->in_time);
                            }
                        }
                       
                    }
                    // if ($shift->segment == 'am' && $dtr->am_in_at) {

                    //     $amInTime = $dtr->am_in_at['time'] ?? null;

                    //     if (!$amInTime) continue;

                    //     $actualIn = Carbon::parse($amInTime);

                    //     $officialStart = Carbon::parse($shift->in_time);

                    //     // cutoff with grace
                    //     $cutoff = Carbon::parse($shift->in_time)
                    //         ->addMinutes($shift->in_grace)
                    //         ->addSeconds(59);

                    //     // LATE computation
                    //     if ($actualIn->gt($cutoff)) {
                    //         $tardiness += $officialStart->diffInMinutes($actualIn);
                    //     }
                    // }
                }

                
                $dtr->update([
                    'tardiness' => $tardiness,
                    'undertime' => $undertime,
                ]);
            }
        }    


        switch($request->option){
            case 'list':
                return $this->view->list($request);
            break;
            default:
            return inertia('Executive/Shifts/Index'); 
        }   
    }
}
