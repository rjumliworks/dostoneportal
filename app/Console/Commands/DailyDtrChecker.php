<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Dtr;
use App\Models\Schedule;
use App\Models\UserShift;
use App\Models\ShiftTime;
use Illuminate\Console\Command;

class DailyDtrChecker extends Command
{
    protected $signature = 'dtr:finalize';

    protected $description = 'Command description';

    public function handle()
    {
        $users = UserShift::with('shift.times')->get();
        foreach ($users as $user) {

            $shift_id = $user->shift->id;
            $shift_name = $user->shift->name;
            $hours = $user->shift->hours;

            $dtrs = Dtr::whereDate('date', Carbon::today())
                ->whereNull('processed_at')
                ->where('user_id', $user->user_id)
                ->get();

            foreach ($dtrs as $dtr) {

                $tardiness = 0;
                $undertime = 0;

                $date = Carbon::parse($dtr->date);

                $weekStart = $date->copy()->startOfWeek(); 
                $weekEnd = $date->copy()->endOfWeek();     

                $fridayHoliday = Schedule::where('event_id',1)->whereBetween('start', [$weekStart, $weekEnd])
                ->whereRaw('DAYOFWEEK(start) = 6') // Friday (MySQL: 6 = Friday)
                ->exists();

                if ($fridayHoliday) {
                    switch($shift_name){
                        case 'Four-Day Work Week':
                            $shifts = ShiftTime::where('shift_id',1)->get();
                        break;
                        case 'Four-Day Work Week (Early Departure)':
                            $shifts = ShiftTime::where('shift_id',2)->get();
                        break;
                        case 'Four-Day Work Week (Late Start)':
                            $shifts = ShiftTime::where('shift_id',3)->get();
                        break;
                    }
                }else{
                    $shifts = $user->shift->times;
                }

                $dayOfWeek = $date->dayOfWeekIso; // Mon=1 ... Sun=7

                $updates = [];

                foreach ($shifts as $shift) {

                    /**
                     * Only process shifts assigned to this day
                     */
                    $days = array_map('trim', explode(',', $shift->days));

                    if (!in_array($dayOfWeek, $days)) {
                        continue;
                    }

                    /**
                     * ==========================
                     * AM SHIFT
                     * ==========================
                     */
                    if ($shift->segment === 'am') {

                        // AM IN
                        if ($dtr->am_in_at) {

                            $am_in_at = json_decode($dtr->am_in_at);

                            $amTime = Carbon::parse($am_in_at->time)->startOfMinute();

                            $officialStart = Carbon::parse($shift->in_time)->startOfMinute();

                            $amLateMinutes = 0;

                            if ($amTime->gt($officialStart)) {
                                $amLateMinutes = $officialStart->diffInMinutes($amTime);
                            }

                            if ($amLateMinutes <= $shift->in_grace) { //!$date->isMonday() && 

                                // Flex schedule
                                $am_in_at->minutes = 0;
                                $am_in_at->temporary_minutes = $amLateMinutes;

                            } else {

                                // Real tardiness
                                $am_in_at->minutes = $amLateMinutes;
                                $am_in_at->temporary_minutes = 0;

                                $tardiness += $amLateMinutes;
                            }

                            $updates['am_in_at'] = json_encode($am_in_at);
                        }

                        // AM OUT
                        if ($dtr->am_out_at) {

                            $am_out_at = json_decode($dtr->am_out_at);

                            $time = Carbon::parse($am_out_at->time)->startOfMinute();
                            $officialOut = Carbon::parse($shift->out_time)->startOfMinute();

                            $computedUndertime = 0;

                            if ($time->lt($officialOut)) {
                                $computedUndertime = $time->diffInMinutes($officialOut);
                            }

                            $undertime += $computedUndertime;

                            $am_out_at->minutes = $computedUndertime;

                            $updates['am_out_at'] = json_encode($am_out_at);
                        }
                    }

                    /**
                     * ==========================
                     * PM SHIFT
                     * ==========================
                     */
                    if ($shift->segment === 'pm') {

                        // PM IN
                        if ($dtr->pm_in_at) {

                            $pm_in_at = json_decode($dtr->pm_in_at);

                            $time = Carbon::parse($pm_in_at->time)->startOfMinute();
                            $officialStart = Carbon::parse($shift->in_time)->startOfMinute();

                            $computedTardiness = 0;

                            if ($time->gt($officialStart)) {
                                $computedTardiness = $officialStart->diffInMinutes($time);
                            }

                            $tardiness += $computedTardiness;

                            $pm_in_at->minutes = $computedTardiness;

                            $updates['pm_in_at'] = json_encode($pm_in_at);
                        }

                        // PM OUT
                        if ($dtr->pm_out_at) {

                            $pm_out_at = json_decode($dtr->pm_out_at);

                            $pmTime = Carbon::parse($pm_out_at->time)->startOfMinute();

                            $adjustedOut = Carbon::parse($shift->out_time)->startOfMinute();

                            
                            if (!$date->isMonday() && $dtr->am_in_at) {

                                $am_in_at = json_decode($dtr->am_in_at);

                                $flexMinutes = 0;

                                if (isset($updates['am_in_at'])) {
                                    $amData = json_decode($updates['am_in_at']);
                                    $flexMinutes = (int) ($amData->temporary_minutes ?? 0);
                                } elseif ($dtr->am_in_at) {
                                    $amData = json_decode($dtr->am_in_at);
                                    $flexMinutes = (int) ($amData->temporary_minutes ?? 0);
                                }

                                $adjustedOut->addMinutes($flexMinutes);
                            }

                            $computedUndertime = 0;

                            if ($pmTime->lt($adjustedOut)) {
                                $computedUndertime = $pmTime->diffInMinutes($adjustedOut);
                            }

                            $undertime += $computedUndertime;

                            $pm_out_at->minutes = $computedUndertime;

                            $updates['pm_out_at'] = json_encode($pm_out_at);
                        }
                    }
                }

                $isHalfDayAm = empty($dtr->am_in_at) && empty($dtr->am_out_at);
                if ($isHalfDayAm && $dtr->pm_in_at) {
                    $updates['is_halfday'] = 1;
                    $updates['hours'] = $hours/2;
                }

                $isHalfDayPm = empty($dtr->pm_in_at) && empty($dtr->pm_out_at);
                if ($isHalfDayPm && $dtr->am_in_at) {
                    $updates['is_halfday'] = 1;
                    $updates['hours'] = $hours/2;
                    $am_in_at = json_decode($dtr->am_in_at);

                    $flex = (int) ($am_in_at->temporary_minutes ?? 0);

                    if ($flex > 0) {

                        // convert flex into real tardiness
                        $am_in_at->minutes = $flex;
                        $am_in_at->temporary_minutes = 0;

                        $tardiness += $flex;

                        $updates['am_in_at'] = json_encode($am_in_at);
                    }
                }

                $amComplete = $dtr->am_in_at !== null && $dtr->am_out_at !== null;
                $pmComplete = $dtr->pm_in_at !== null && $dtr->pm_out_at !== null;

                $isHalfDayAm = empty($dtr->am_in_at) && empty($dtr->am_out_at);
                $isHalfDayPm = empty($dtr->pm_in_at) && empty($dtr->pm_out_at);

                $isFullDay = $amComplete && $pmComplete;

                if ($isFullDay) {
                    $updates['is_completed'] = 1;
                } elseif ($isHalfDayAm && $dtr->pm_in_at !== null && $dtr->pm_out_at !== null) {
                    $updates['is_completed'] = 1;
                } elseif ($isHalfDayPm && $dtr->am_in_at !== null && $dtr->am_out_at !== null) {
                    $updates['is_completed'] = 1;
                } else {
                    $updates['is_completed'] = 0;
                }

               
                $updates['tardiness'] = $tardiness;
                $updates['undertime'] = $undertime;
                $updates['shift_id'] =  $shift_id;
                $updates['processed_at'] = now();
                $dtr->update($updates);
            }
        }
    }
}
