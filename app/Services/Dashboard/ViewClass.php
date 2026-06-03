<?php

namespace App\Services\Dashboard;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\Dtr;
use App\Models\OrgChart;
use App\Models\UserProfile;
use App\Models\Schedule;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HumanResource\Dtr\IndexResource;
use App\Http\Resources\Executive\Signatory\ListResource;

class ViewClass
{
    public function birthdays(){
        return UserProfile::where('birthmonth', date('m'))
        ->get()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'avatar' => $user->avatar,
                'fullname' => $user->fullname,
                'birthdate' => $user->birthdate,
            ];
        });
    }

    public function dtr(){
        $dtr = Dtr::where('user_id', Auth::id())
        ->whereDate('created_at', date('Y-m-d'))
        ->first();

        return $dtr ? new IndexResource($dtr) : null;
    }

     public function designations(){
        $data = OrgChart::with('designation','assigned')
        ->with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar','oic:id,email,username','oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar')
        ->whereIn('id',[1,2,3])
        ->orderBy('order','ASC')
        ->get();
        return ListResource::collection($data);
    }

    public function attendance(){
        $user_id = Auth::id();
        $year  = date('Y');
        $month = date('m');
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $dtrDates = Dtr::where('user_id', $user_id)
        ->whereBetween('date', [$start, $end])
        ->pluck('date')
        ->flip();

        $holidays = Schedule::where('event_id', 31)
        ->where(function ($q) use ($start, $end) {
            $q->whereBetween('start', [$start, $end])
            ->orWhereBetween('end', [$start, $end]);
        })
        ->get()
        ->flatMap(function ($holiday) use ($start, $end) {
            $from = Carbon::parse($holiday->start)->max($start);
            $to   = Carbon::parse($holiday->end ?? $holiday->start)->min($end);

            return CarbonPeriod::create($from, $to)
                ->map(fn($d) => $d->toDateString());
        })
        ->flip();

        $travels = Request::where('type_id', 156)
            ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
            ->whereHas('dates', function ($q) use ($start, $end) {
                $q->whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end]);
            })
            ->with('dates')
            ->get();

        foreach ($travels as $travel) {
            foreach ($travel->dates as $date) {
                $from = Carbon::parse($date->start)->max($start);
                $to   = Carbon::parse($date->end ?? $date->start)->min($end);

                foreach (CarbonPeriod::create($from, $to) as $d) {
                    $travelDays[$d->toDateString()] = true;
                }
            }
        }

        $businessDays = [];

        $obs = Request::where('type_id', 192)
            ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
            ->whereHas('dates', function ($q) use ($start, $end) {
                $q->whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end]);
            })
            ->with('dates')
            ->get();

        foreach ($obs as $ob) {
            foreach ($ob->dates as $date) {
                $from = Carbon::parse($date->start)->max($start);
                $to   = Carbon::parse($date->end ?? $date->start)->min($end);

                foreach (CarbonPeriod::create($from, $to) as $d) {
                    $businessDays[$d->toDateString()] = true;
                }
            }
        }

        $absences = 0;

        $period = CarbonPeriod::create($start, $end);

foreach ($period as $date) {
    $dateStr = $date->toDateString();

    // skip future days (still in month)
    if ($date->greaterThan(now())) continue;

    // skip weekends
    if ($date->isWeekend()) continue;

    // skip holidays
    if (isset($holidays[$dateStr])) continue;

    // skip travel
    if (isset($travelDays[$dateStr])) continue;

    // skip business
    if (isset($businessDays[$dateStr])) continue;

    // ABSENT if no DTR
    if (!isset($dtrDates[$dateStr])) {
        $absences++;
    }
}

        return [
            Dtr::where('user_id', Auth::id())->whereMonth('created_at', date('m'))->where('is_completed',0)->count(),
            Dtr::where('user_id', Auth::id())->whereMonth('created_at', date('m'))->where(function ($q) { $q->where('tardiness', '>', 0)->orWhere('undertime', '>', 0); })->count(),
            $absences
        ];
    }

    public function whereabouts(){
        $date = Carbon::today()->toDateString();
        $users = User::with([
            'dtrs' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            }
        ])->get();

        $travels = Request::where('type_id', 156)
            ->whereHas('dates', function ($q) use ($date) {
                $q ->whereDate('start', '<=', $date)
                ->whereDate('end', '>=', $date);
            })
            ->with('tags')
            ->get()
            ->pluck('tags.*.user_id')
            ->flatten()
            ->unique()
            ->flip();

        $business = Request::where('type_id', 192)
             ->whereHas('dates', function ($q) use ($date) {
                $q ->whereDate('start', '<=', $date)
                ->whereDate('end', '>=', $date);
            })
            ->with('tags')
            ->get()
            ->pluck('tags.*.user_id')
            ->flatten()
            ->unique()
            ->flip();
            

            $result = [];

            foreach ($users as $user) {

                $hasDtr = $user->dtrs->isNotEmpty();

                if ($travels->has($user->id) || $business->has($user->id)) {
                    $status = 'Official Travel';
                }
                elseif ($hasDtr) {
                    $status = 'Present';
                }
                else {
                    $status = 'Absent';
                }

                $result[] = [
                    'user_id' => $user->id,
                    'name' => $user->profile->fullname,
                    'avatar' => $user->profile?->avatar,
                    'status' => $status,
                ];
            }
            return $result;
    }
}
