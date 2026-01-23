<?php

namespace App\Services\Portal\Dtr;

use Carbon\Carbon;
use App\Models\Dtr;
use App\Models\Request;
use App\Models\Schedule;
use App\Models\UserProfile;

class PrintClass
{
    public function dtr($request){
        $year = $request->year;
        $month = $request->month;
        $user_id = $request->id;

        $user = UserProfile::select('id','user_id','firstname','lastname','middlename','suffix_id')->where('user_id',$user_id)->first();

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

        $holidays = Schedule::whereBetween('start', [$startOfMonth, $endOfMonth]) // starts this month
        ->orWhereBetween('end', [$startOfMonth, $endOfMonth]) // ends this month
        ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) { // spans whole month
            $q2->where('start', '<', $startOfMonth)
                ->where('end', '>', $endOfMonth);
        })
        ->where('event_id',31)
        ->get();


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

            if($day == 'Saturday' || $day == 'Sunday'){
                $array[] = [
                    'date' => date('Y-m-d', $i),
                    'text' => date('F d, Y', $i),
                    'day' => date('l', $i),
                    'data' => 'NON-WORKING DAY',
                    'bg' => 'bg bg-secondary bg-soft',
                    'is_with' => false
                ];
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
            }else if($holidayToday){
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
                            'data' => 'ABSENT',
                            'bg' => 'bg bg-danger bg-soft',
                            'is_with' => false
                        ];
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
            'year' => $year
        ];

        $pdf = \PDF::loadView('prints.dtr',$array)->setPaper('a4', 'portrait');
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
      
        return $pdf->stream($month.'-'.$year.'.pdf');
    }
}
