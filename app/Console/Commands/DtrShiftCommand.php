<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Dtr;
use App\Models\UserShift;
use Illuminate\Console\Command;

class DtrShiftCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
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
                            if($date->isMonday()) {
                                $start_time = $shift->in_time;
                            }else{
                                $time = Carbon::parse($d->time);
                                $officialStart = Carbon::parse($shift->in_time);
                                $flexibleCutoff = Carbon::parse($shift->in_time)->addMinutes($shift->in_grace);
                                $tardiness= ($time->greaterThan($flexibleCutoff)) ? (int) $officialStart->diffInMinutes($time) : 0;
                            }
                        }
                        if($dtr->am_out_at){
                            $d = json_decode($dtr->am_out_at);
                            $time = Carbon::parse($d->time);
                            $officialMorningOut = Carbon::parse($shift->out_time);
                            $undertime = ($time->lessThan($officialMorningOut)) ? ceil($time->diffInMinutes($officialMorningOut)) : 0;
                        }
                       
                    }else if($shift->segment == 'pm'){
                         if($dtr->pm_in_at){
                            $d = json_decode($dtr->pm_in_at);
                            $time = Carbon::parse($d->time);
                            $officialStart = Carbon::parse($shift->in_time);
                            $tardiness = $time->greaterThan($officialStart) ? (int) $officialStart->diffInMinutes($time) : 0;
                            
                        }
                        if($dtr->pm_out_at){
                            $d = json_decode($dtr->pm_out_at);
                            $time = Carbon::parse($d->time);
                            $officialMorningOut = Carbon::parse($shift->out_time);
                            $undertime = ($time->lessThan($officialMorningOut)) ? ceil($time->diffInMinutes($officialMorningOut)) : 0;
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
        
    }
}
