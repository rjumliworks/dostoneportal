<?php

namespace App\Services\HumanResource\Dtr;

use Carbon\Carbon;
use App\Models\Dtr;
use App\Models\Request;
use App\Models\Schedule;
use App\Models\UserShift;
use App\Models\UserProfile;
use App\Models\UserOrganization;
use App\Models\ListDropdown;
use App\Http\Resources\HumanResource\Dtr\OldResource;
use App\Http\Resources\HumanResource\Dtr\IndexResource;

class PrintClass
{
    public function dtr($request){
        $year = $request->year;
        $month = $request->month;
        $user_id = $request->id;

        $user = UserProfile::select('id','user_id','firstname','lastname','middlename','suffix_id')->where('user_id',$user_id)->first();
        $shift = UserShift::with('shift.times')->where('user_id',$user_id)->first();
        $station_id = UserOrganization::where('user_id',$user_id)->value('station_id');
        $type_id = UserOrganization::where('user_id',$user_id)->value('type_id');

        $month_number = date("n", strtotime($month));
        $today = date('Y-m-d', strtotime(now()));
        $start_time = strtotime("01-".$month_number."-".$year);
        $end_time = strtotime("+1 month", $start_time);

        $startOfMonth = date("$year-$month_number-01");
        $endOfMonth = date("Y-m-t", strtotime($startOfMonth));

        $travels = Request::where('type_id',156)
        ->whereHas('tags', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->whereHas('dates', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('start', [$startOfMonth, $endOfMonth]) // starts this month
            ->orWhereBetween('end', [$startOfMonth, $endOfMonth]) // ends this month
            ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) { // spans whole month
                $q2->where('start', '<', $startOfMonth)
                    ->where('end', '>', $endOfMonth);
            });
        })
        ->with('dates','detail')
        ->get();

        $obs = Request::where('type_id',192)
        ->whereHas('tags', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->whereHas('dates', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('start', [$startOfMonth, $endOfMonth]) // starts this month
            ->orWhereBetween('end', [$startOfMonth, $endOfMonth]) // ends this month
            ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) { // spans whole month
                $q2->where('start', '<', $startOfMonth)
                    ->where('end', '>', $endOfMonth);
            });
        })
        ->with('dates','event','detail')
        ->get();

        $holidays = Schedule::where(function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('start', [$startOfMonth, $endOfMonth])
            ->orWhereBetween('end', [$startOfMonth, $endOfMonth])
            ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) {
                $q2->where('start', '<', $startOfMonth)
                    ->where('end', '>', $endOfMonth);
            });
        })
        ->whereHas('stations', function ($q) use ($station_id) {
            $q->where('station_id', $station_id);
        })
        ->where('event_id',1)
        ->with('event')
        ->get();

        $suspensions = Schedule::where(function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('start', [$startOfMonth, $endOfMonth])
            ->orWhereBetween('end', [$startOfMonth, $endOfMonth])
            ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) {
                $q2->where('start', '<', $startOfMonth)
                    ->where('end', '>', $endOfMonth);
            });
        })
        ->whereHas('stations', function ($q) use ($station_id) {
            $q->where('station_id', $station_id);
        })
        ->where('event_id',2)
        ->with('event')
        ->get();

        $uniqueDays = $shift->shift->times->pluck('days') 
        ->flatMap(function ($days) {        
            return explode(',', $days);
        })->unique()->values();


        for($i=$start_time; $i<$end_time; $i+=86400){
            $date = date('Y-m-d', $i);
            $day = date('l', $i);
            $date2 = Carbon::createFromTimestamp($i);

            $travelToday = $travels->first(function ($t) use ($date2) {
                return $t->dates->contains(function ($d) use ($date2) {
                    $start = Carbon::parse($d->start);
                    $end   = Carbon::parse($d->end);
                    return $date2->between($start, $end);
                });
            });

            $obToday = $obs->first(function ($t) use ($date2) {
                return $t->dates->contains(function ($d) use ($date2) {
                    $start = Carbon::parse($d->start);
                    $end   = Carbon::parse($d->end);
                    return $date2->between($start, $end);
                });
            });

            $holidayToday = $holidays->first(function ($t) use ($date2) {
                $start = Carbon::parse($t->start)->startOfDay();
                $end   = Carbon::parse($t->end)->endOfDay();
                return $date2->between($start, $end, true);
            });

            $suspensionToday = $suspensions->first(function ($t) use ($date2) {
                $start = Carbon::parse($t->start)->startOfDay();
                $end   = Carbon::parse($t->end)->endOfDay();
                return $date2->between($start, $end, true);
            });

            $dayNames = [
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday',
            ];

            $nonWorkingDays = collect(range(1, 7))
            ->diff($uniqueDays)
            ->map(fn ($day) => $dayNames[$day])
            ->values();

             if($holidayToday){
                $array[] = [
                    'date' => date('Y-m-d', $i),
                    'text' => date('F d, Y', $i),
                    'day' => date('l', $i),
                    'data' => 'HOLIDAY', 
                    'title' => $holidayToday->title,
                    'bg' => 'bg bg-info bg-soft',
                    'is_with' => false
                ];
                continue;
           }else if ($nonWorkingDays->contains($day)) {
                $array[] = [
                    'date' => date('Y-m-d', $i),
                    'text' => date('F d, Y', $i),
                    'day' => date('l', $i),
                    'data' => 'NON-WORKING DAY',
                    'bg' => 'bg bg-secondary bg-soft',
                    'is_with' => false
                ];
                continue;
            }else if($travelToday){
                $array[] = [
                    'date' => date('Y-m-d', $i),
                    'text' => date('F d, Y', $i),
                    'day' => date('l', $i),
                    'data' => 'OFFICIAL TRAVEL', // adjust if different
                    'destination' => $travelToday->location->address.', '.$travelToday->location->municipality->name,
                    'purpose' => $travelToday->detail->purpose,
                    'bg' => 'bg bg-info bg-soft',
                    'is_with' => false,
                    'travel_group' => $travelToday->id
                ];
                continue;
            }else if($obToday){
                $array[] = [
                    'date' => date('Y-m-d', $i),
                    'text' => date('F d, Y', $i),
                    'day' => date('l', $i),
                    'data' => 'OFFICIAL BUSINESS', // adjust if different
                    'title' => $obToday->event->title,
                    'purpose' => $obToday->detail->purpose,
                    'bg' => 'bg bg-info bg-soft',
                    'is_with' => false,
                    'travel_group' => $obToday->id
                ];
                continue;
            }else if($suspensionToday){
                $data = Dtr::whereDate('date', $date)
                    ->where('user_id', $user_id)
                    ->first();

                if($data){

                    $array[] = [
                        'date' => date('Y-m-d', $i),
                        'text' => date('F d, Y', $i),
                        'day' => date('l', $i),
                        'data' => 'WORK SUSPENDED',
                        'title' => $suspensionToday->event->name,
                        'bg' => 'bg bg-info bg-soft',
                        'is_with' => false
                    ];

                }else{

                    $array[] = [
                        'date' => date('Y-m-d', $i),
                        'text' => date('F d, Y', $i),
                        'day' => date('l', $i),
                        'data' => '',
                        'bg' => 'bg bg-danger bg-soft',
                        'is_with' => false
                    ];

                }

                continue;
            }else{
                $data = Dtr::whereDate('date',$date)->where('user_id',$user_id)->first();
          
                if($data){
                    if($data->am_in_at == '[]' || $data->am_out_at == '[]' || $data->pm_out_at == '[]' || $data->pm_in_at == '[]'){
                        $is_completed = false;
                    }else{
                        $is_completed = true;
                    }
                    $chck = ($date < $today) ? 'bg bg-soft bg-warning' : '';
                    
                    $array[] = [
                        'date' => date('Y-m-d', $i),
                        'text' => date('F d, Y', $i),
                        'day' => date('l', $i),
                        'data' => [
                            'am_in' => ($data['am_in_at']) ? \Carbon\Carbon::parse(json_decode($data['am_in_at'])->time)->format('h:i') : null,
                            'am_out' => ($data['am_out_at']) ? \Carbon\Carbon::parse(json_decode($data['am_out_at'])->time)->format('h:i') : null,
                            'pm_in' => ($data['pm_in_at']) ? \Carbon\Carbon::parse(json_decode($data['pm_in_at'])->time)->format('h:i') : null,
                            'pm_out' => ($data['pm_out_at']) ? \Carbon\Carbon::parse(json_decode($data['pm_out_at'])->time)->format('h:i') : null,
                        ],
                        'bg' => ($is_completed) ? 'bg bg-soft bg-success' : $chck ,
                        'is_with' => true
                    ];
                }else{
                    if($date < $today){
                        $array[] =  [
                            'date' => date('Y-m-d', $i),
                            'text' => date('F d, Y', $i),
                            'day' => date('l', $i),
                            'data' => '',
                            'bg' => 'bg bg-danger bg-soft',
                            'is_with' => false
                        ];
                        // $array[] =  [
                        //     'date' => date('Y-m-d', $i),
                        //     'text' => date('F d, Y', $i),
                        //     'day' => date('l', $i),
                        //     'data' => [],
                        //     'bg' => '',
                        //     'is_with' => true
                        // ];
                    }else{
                        $array[] =  [
                            'date' => date('Y-m-d', $i),
                            'text' => date('F d, Y', $i),
                            'day' => date('l', $i),
                            'data' => [],
                            'bg' => '',
                            'is_with' => true
                        ];
                    }
                }
            }
        }
   
        $array = [
            'lists' => $array,
            'user' => $user,
            'month' => $month,
            'year' => $year,
            'type' => $type_id
        ];

        $pdf = \PDF::loadView('prints.dtr',$array)->setPaper('a4', 'portrait');
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
      
        return $pdf->stream($month.'-'.$year.'.pdf');
    }
    // public function dtr($request){
    //     $year = $request->year;
    //     $month = $request->month;
    //     $user_id = $request->id;

    //     $user = UserProfile::select('id','user_id','firstname','lastname','middlename','suffix_id')->where('user_id',$user_id)->first();

    //     $month_number = date("n", strtotime($month));
    //     $today = date('Y-m-d', strtotime(now()));
    //     $start_time = strtotime("01-".$month_number."-".$year);
    //     $end_time = strtotime("+1 month", $start_time);

    //     $startOfMonth = date("$year-$month_number-01");
    //     $endOfMonth = date("Y-m-t", strtotime($startOfMonth));

    //     $travels = Request::where('type_id',156)
    //     ->whereHas('tags', function ($query) use ($user_id) {
    //         $query->where('user_id', $user_id);
    //     })
    //     ->whereHas('dates', function ($q) use ($startOfMonth, $endOfMonth) {
    //         $q->whereBetween('start', [$startOfMonth, $endOfMonth]) // starts this month
    //         ->orWhereBetween('end', [$startOfMonth, $endOfMonth]) // ends this month
    //         ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) { // spans whole month
    //             $q2->where('start', '<', $startOfMonth)
    //                 ->where('end', '>', $endOfMonth);
    //         });
    //     })
    //     ->with('dates','detail')
    //     ->get();

    //     $obs = Request::where('type_id',192)
    //     ->whereHas('tags', function ($query) use ($user_id) {
    //         $query->where('user_id', $user_id);
    //     })
    //     ->whereHas('dates', function ($q) use ($startOfMonth, $endOfMonth) {
    //         $q->whereBetween('start', [$startOfMonth, $endOfMonth]) // starts this month
    //         ->orWhereBetween('end', [$startOfMonth, $endOfMonth]) // ends this month
    //         ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) { // spans whole month
    //             $q2->where('start', '<', $startOfMonth)
    //                 ->where('end', '>', $endOfMonth);
    //         });
    //     })
    //     ->with('dates','event','detail')
    //     ->get();

    //     $holidays = Schedule::whereBetween('start', [$startOfMonth, $endOfMonth]) // starts this month
    //     ->orWhereBetween('end', [$startOfMonth, $endOfMonth]) // ends this month
    //     ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) { // spans whole month
    //         $q2->where('start', '<', $startOfMonth)
    //             ->where('end', '>', $endOfMonth);
    //     })
    //     ->where('event_id',31)
    //     ->get();


    //     for($i=$start_time; $i<$end_time; $i+=86400){
    //         $date = date('Y-m-d', $i);
    //         $day = date('l', $i);
    //         $date2 = Carbon::createFromTimestamp($i);

    //         $travelToday = $travels->first(function ($t) use ($date2) {
    //             return $t->dates->contains(function ($d) use ($date2) {
    //                 $start = Carbon::parse($d->start);
    //                 $end   = Carbon::parse($d->end);
    //                 return $date2->between($start, $end);
    //             });
    //         });

    //         $obToday = $obs->first(function ($t) use ($date2) {
    //             return $t->dates->contains(function ($d) use ($date2) {
    //                 $start = Carbon::parse($d->start);
    //                 $end   = Carbon::parse($d->end);
    //                 return $date2->between($start, $end);
    //             });
    //         });

    //         $holidayToday = $holidays->first(function ($t) use ($date2) {
    //             $start = Carbon::parse($t->start)->startOfDay();
    //             $end   = Carbon::parse($t->end)->endOfDay();
    //             return $date2->between($start, $end, true);
    //         });

    //         if($day == 'Saturday' || $day == 'Sunday'){
    //             $array[] = [
    //                 'date' => date('Y-m-d', $i),
    //                 'text' => date('F d, Y', $i),
    //                 'day' => date('l', $i),
    //                 'data' => 'NON-WORKING DAY',
    //                 'bg' => 'bg bg-secondary bg-soft',
    //                 'is_with' => false
    //             ];
                
    //         }else if($travelToday){
    //             $array[] = [
    //                 'date' => date('Y-m-d', $i),
    //                 'text' => date('F d, Y', $i),
    //                 'day' => date('l', $i),
    //                 'data' => 'OFFICIAL TRAVEL', // adjust if different
    //                 'destination' => $travelToday->location->address.', '.$travelToday->location->municipality->name,
    //                 'purpose' => $travelToday->detail->purpose,
    //                 'bg' => 'bg bg-info bg-soft',
    //                 'is_with' => false,
    //                 'travel_group' => $travelToday->id
    //             ];
    //             continue;
    //         }else if($obToday){
    //             $array[] = [
    //                 'date' => date('Y-m-d', $i),
    //                 'text' => date('F d, Y', $i),
    //                 'day' => date('l', $i),
    //                 'data' => 'OFFICIAL BUSINESS', // adjust if different
    //                 'title' => $obToday->event->title,
    //                 'purpose' => $obToday->detail->purpose,
    //                 'bg' => 'bg bg-info bg-soft',
    //                 'is_with' => false,
    //                 'travel_group' => $obToday->id
    //             ];
    //             continue;
    //         }else if($holidayToday){
    //             $array[] = [
    //                 'date' => date('Y-m-d', $i),
    //                 'text' => date('F d, Y', $i),
    //                 'day' => date('l', $i),
    //                 'data' => 'HOLIDAY', 
    //                 'title' => $holidayToday->title,
    //                 'bg' => 'bg bg-info bg-soft',
    //                 'is_with' => false
    //             ];
    //             continue;
    //         }else{
    //             $data = Dtr::whereDate('date',$date)->where('user_id',$user_id)->first();
          
    //             if($data){
    //                 if($data->am_in_at == '[]' || $data->am_out_at == '[]' || $data->pm_out_at == '[]' || $data->pm_in_at == '[]'){
    //                     $is_completed = false;
    //                 }else{
    //                     $is_completed = true;
    //                 }
    //                 $chck = ($date < $today) ? 'bg bg-soft bg-warning' : '';
                    
    //                 $array[] = [
    //                     'date' => date('Y-m-d', $i),
    //                     'text' => date('F d, Y', $i),
    //                     'day' => date('l', $i),
    //                     'data' => [
    //                         'am_in' => ($data['am_in_at']) ? \Carbon\Carbon::parse(json_decode($data['am_in_at'])->time)->format('h:i') : null,
    //                         'am_out' => ($data['am_out_at']) ? \Carbon\Carbon::parse(json_decode($data['am_out_at'])->time)->format('h:i') : null,
    //                         'pm_in' => ($data['pm_in_at']) ? \Carbon\Carbon::parse(json_decode($data['pm_in_at'])->time)->format('h:i') : null,
    //                         'pm_out' => ($data['pm_out_at']) ? \Carbon\Carbon::parse(json_decode($data['pm_out_at'])->time)->format('h:i') : null,
    //                     ],
    //                     'bg' => ($is_completed) ? 'bg bg-soft bg-success' : $chck ,
    //                     'is_with' => true
    //                 ];
    //             }else{
    //                 if($date < $today){
    //                     $array[] =  [
    //                         'date' => date('Y-m-d', $i),
    //                         'text' => date('F d, Y', $i),
    //                         'day' => date('l', $i),
    //                         'data' => 'ABSENT',
    //                         'bg' => 'bg bg-danger bg-soft',
    //                         'is_with' => false
    //                     ];
    //                 }else{
    //                     $array[] =  [
    //                         'date' => date('Y-m-d', $i),
    //                         'text' => date('F d, Y', $i),
    //                         'day' => date('l', $i),
    //                         'data' => [],
    //                         'bg' => '',
    //                         'is_with' => true
    //                     ];
    //                 }
    //             }
    //         }
    //     }
   
    //     $array = [
    //         'lists' => $array,
    //         'user' => $user,
    //         'month' => $month,
    //         'year' => $year
    //     ];

    //     $pdf = \PDF::loadView('prints.dtr',$array)->setPaper('a4', 'portrait');
    //     $pdf->output();
    //     $dompdf = $pdf->getDomPDF();
    //     $canvas = $dompdf->getCanvas();
      
    //     return $pdf->stream($month.'-'.$year.'.pdf');
    // }

    public function bulk($request){
        $year = $request->year;
        $monthName = $request->month;
        $month = Carbon::parse("1 $monthName")->month;
        $station = $request->station;
        $data = Dtr::with('user.profile')
        ->where('station_id',$station)
        ->whereMonth('created_at',$month)
        ->whereYear('created_at',$year)
        ->orderBy('date')
        ->get();

        $grouped = $data->groupBy('date');
        $lists = $grouped->map(function ($items) {
            return IndexResource::collection($items)->resolve(); 
        })->toArray(); // 🔥 convert to array

        $pdf = \PDF::loadView('prints.bulk', [
            'lists' => $lists,
            'station' => ListDropdown::where('id',$station)->first()
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($month . '-' . $year . '.pdf');
    }

    public function old($request)
{
    $year = $request->year;
    $monthName = $request->month;
    $month = Carbon::parse("1 $monthName")->month;
    $station = $request->station;

    $data = Dtr::with('user.profile','olds') // eager load olds relationship
        ->where('station_id', $station)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('date')
        ->get();

    $grouped = $data->groupBy('date');

    $lists = $grouped->map(function ($items) {
        return OldResource::collection($items)->resolve();
    })->toArray();

    $pdf = \PDF::loadView('prints.old', [
        'lists' => $lists,
        'station' => ListDropdown::find($station)
    ])->setPaper('a4', 'portrait');

    return $pdf->stream($month . '-' . $year . '.pdf');
}
}
