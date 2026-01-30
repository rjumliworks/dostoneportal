<?php

namespace App\Services\HumanResource\Dashboard;

use App\Models\Dtr;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Http\Resources\DefaultResource;

class TopClass
{
    public function absences($request){
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

        // 1️⃣ Generate working days (Mon–Fri)
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth();

        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
        $workingDays = [];
        foreach($period as $date){
            if(!$date->isWeekend()){ // only Mon–Fri
                $workingDays[] = $date->toDateString();
            }
        }

        // 2️⃣ Get all users (active only if needed)
        $users = User::with('profile')
            ->whereHas('organization', function($q){
               
            })
            ->get();

        // 3️⃣ Calculate absences per user
        $usersWithAbsences = $users->map(function($user) use ($workingDays){
            $presentDays = $user->dtrs()
                ->whereIn('date', $workingDays)
                ->pluck('date')
                ->toArray();

            $absentCount = count(array_diff($workingDays, $presentDays));

            if($absentCount > 0){
                $user->absences_count = $absentCount;
                return $user;
            }
        })
        ->filter() // remove users with 0 absences
        ->sortByDesc('absences_count') // sort descending by absence count
        ->values()
        ->take(100); // top 10

        return $usersWithAbsences;
    }
}
