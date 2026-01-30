<?php

namespace App\Services\HumanResource\Dashboard;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Dtr;
use App\Models\User;

class BarClass
{
    public function chart($request)
    {
        $today = Carbon::today();

        $currentWeek = $request->week ? Carbon::parse($request->week) : $today;

        $startOfWeek = Carbon::parse($currentWeek);
        $endOfWeek = $startOfWeek->copy()->addDays(4);

        if ($startOfWeek->greaterThan($today)) {
            $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
            $endOfWeek   = $today->copy()->endOfWeek(Carbon::MONDAY);
        }

        $totalUsers = User::whereHas('organization', function ($q) {
            // $q->where('status_id', 2);
        })->count();

        $categories = [];
        $present = [];
        $late = [];
        $absent = [];

        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);

        foreach ($period as $date) {
            if ($date->isWeekend()) { continue; }
            if ($date->greaterThan($today)) { continue; }

            $presentCount = Dtr::whereDate('date', $date)->distinct('user_id')->count('user_id');

            $lateCount = Dtr::whereDate('date', $date)->where(function ($q) {
                    $q->where('undertime', '!=', 0)
                        ->orWhere('tardiness', '!=', 0);
                }) ->distinct('user_id')->count('user_id');

            $absentCount = max(0, $totalUsers - $presentCount);

            $categories[] = $date->format('l'); // Mon–Fri
            $present[] = $presentCount;
            $late[] = $lateCount;
            $absent[] = $absentCount;
        }

        return [
            'range' => [
                'start' => $startOfWeek->toDateString(),
                'end'   => min($endOfWeek->toDateString(), $today->toDateString()),
            ],
            'categories' => $categories,
            'lists' => [
                ['name' => 'Present', 'data' => $present],
                ['name' => 'Late', 'data' => $late],
                ['name' => 'Absent', 'data' => $absent],
            ],
        ];
    }

}
