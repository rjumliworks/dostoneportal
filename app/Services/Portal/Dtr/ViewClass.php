<?php

namespace App\Services\Portal\Dtr;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
// use App\Models\Request;
use App\Http\Resources\Portal\Dtr\TimeResource;

class ViewClass
{
    public function dtr($request){
        $user = \Auth::user()->id;
        $cutoff_id = $request->cutoff_id;
        $is_regular = $request->is_regular;
        
        $monthName = $request->month;
        $year  = ($request->year) ? $request->year : date('Y');
        $month = Carbon::parse($monthName)->month;

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $data =  User::with([
            'profile',
            'organization.position',
            'organization.division',
            'organization.type',
            'payrolls' => function ($q) use ($cutoff_id) {
                $q->where('cutoff_id', $cutoff_id);
            },
            'dtrs' => function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end]);
            }
        ])
        ->when(!is_null($is_regular) && $is_regular == 1, function ($query) {
            $query->whereHas('organization', function ($query) {
                $query->where('type_id', 15);
            });
        })
        ->where('id',$user)
        ->first();

        return $this->formatDTR($data, $start, $end);
    }

    private function formatDTR($item, $start, $end)
    {
        $alreadyInPayroll = $item->payrolls->isNotEmpty();
        $user_id = $item->id;

        $period = \Carbon\CarbonPeriod::create($start, $end);

        /**
         * =========================
         *  HOLIDAYS
         * =========================
         */
        $holidays = Schedule::whereBetween('start', [$start, $end])
            ->orWhereBetween('end', [$start, $end])
            ->orWhere(function ($q2) use ($start, $end) {
                $q2->where('start', '<', $start)
                    ->where('end', '>', $end);
            })
            ->where('event_id', 31)
            ->get(['start', 'title'])
            ->flatMap(function ($holiday) {
                $list = [];
                $startDate = \Carbon\Carbon::parse($holiday->start);
                $endDate = \Carbon\Carbon::parse($holiday->end ?? $holiday->start);

                foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $day) {
                    $list[$day->format('Y-m-d')] = $holiday->title;
                }
                return $list;
            });

        $ignoreDates = array_keys($holidays->toArray());

        /**
         * =========================
         *  OFFICIAL TRAVEL
         * =========================
         */
        // $officialTravel = [];
        // $travels = Request::where('type_id', 156)
        //     ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
        //     ->whereHas('dates', function ($q) use ($start, $end) {
        //         $q->whereBetween('start', [$start, $end])
        //             ->orWhereBetween('end', [$start, $end])
        //             ->orWhere(function ($q2) use ($start, $end) {
        //                 $q2->where('start', '<', $start)
        //                     ->where('end', '>', $end);
        //             });
        //     })
        //     ->with('dates', 'detail', 'location', 'location.municipality')
        //     ->get();

        // foreach ($travels as $travel) {
        //     foreach ($travel->dates as $travelDate) {
        //         $period2 = \Carbon\CarbonPeriod::create($travelDate->start, $travelDate->end ?? $travelDate->start);
        //         foreach ($period2 as $day) {
        //             $officialTravel[$day->format('Y-m-d')] =
        //                 ($travel->location->address . ', ' . $travel->location->municipality->name)
        //                 ?? 'Official Travel';
        //         }
        //     }
        // }

        /**
         * =========================
         *  OFFICIAL BUSINESS
         * =========================
         */
        // $officialBusiness = [];
        // $obs = Request::where('type_id', 192)
        //     ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
        //     ->whereHas('dates', function ($q) use ($start, $end) {
        //         $q->whereBetween('start', [$start, $end])
        //             ->orWhereBetween('end', [$start, $end])
        //             ->orWhere(function ($q2) use ($start, $end) {
        //                 $q2->where('start', '<', $start)
        //                     ->where('end', '>', $end);
        //             });
        //     })
        //     ->with('dates', 'event')
        //     ->get();

        // foreach ($obs as $ob) {
        //     foreach ($ob->dates as $obDate) {
        //         $period3 = \Carbon\CarbonPeriod::create($obDate->start, $obDate->end ?? $obDate->start);
        //         foreach ($period3 as $day) {
        //             $officialBusiness[$day->format('Y-m-d')] = $ob->event->title ?? 'Official Business';
        //         }
        //     }
        // }

        /**
         * =========================
         *  DAILY LOOP
         * =========================
         */
        $result = [];

        foreach ($period as $date) {
            $dateStr = $date->toDateString();

            // if (in_array($dateStr, $ignoreDates) ||
            //     $date->isWeekend()) {
            //     continue;
            // }

            // status resolution
            $status = null;
            $title = null;

            if ($date->isWeekend()) {
                $status = 'Non-working Day';
                $title = 'Non-working Day';
            } elseif (isset($holidays[$dateStr])) {
                $status = 'Holiday';
                $title = $holidays[$dateStr];
            } 
            // elseif (isset($officialTravel[$dateStr])) {
            //     $status = 'Official Travel';
            //     $title = $officialTravel[$dateStr];
            // } elseif (isset($officialBusiness[$dateStr])) {
            //     $status = 'Official Business';
            //     $title = $officialBusiness[$dateStr];
            // }

            $dtr = $item->dtrs->firstWhere('date', $dateStr);
            $forceCompleted = in_array($status, ['Official Travel', 'Official Business']);
            $today = Carbon::today();
            $isFutureDate = $date->greaterThanOrEqualTo($today);
            $result[] = [
                'date' => $dateStr,
                'am_in'  => $dtr && $dtr->am_in_at  ? new TimeResource(json_decode($dtr->am_in_at)) : null,
                'am_out' => $dtr && $dtr->am_out_at ? new TimeResource(json_decode($dtr->am_out_at)) : null,
                'pm_in'  => $dtr && $dtr->pm_in_at  ? new TimeResource(json_decode($dtr->pm_in_at))  : null,
                'pm_out' => $dtr && $dtr->pm_out_at ? new TimeResource(json_decode($dtr->pm_out_at)) : null,
                'is_completed' => $forceCompleted ? 1 : ($dtr?->is_completed),
                'status' => $status ?? ($dtr ? 'Present' : ($isFutureDate ? '-' : 'Absent')),
                'title' => $title
            ];
        }

        return [
            'value' => $item->id,
            'name' => $item->profile->lastname . ', ' . $item->profile->firstname . ' ' . $item->profile->middlename . '.',
            'position' => optional($item->organization->position)->name,
            'division' => optional($item->organization->division)->name,
            'division_id' => optional($item->organization->division)->id,
            'type' => $item->organization->type->name,
            'avatar' => ($item->profile?->avatar && $item->profile->avatar !== 'noavatar.jpg')
                ? asset('storage/' . $item->profile->avatar)
                : asset('images/avatars/avatar.jpg'),
            'already_in_payroll' => $alreadyInPayroll,
            'dtrs' => $alreadyInPayroll ? [] : $result
        ];
    }
}
