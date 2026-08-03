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
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HumanResource\Dtr\IndexResource;
use App\Http\Resources\Trace\Signatory\ListResource;

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

        $organization = UserOrganization::with('shift.times')->where('user_id', $user_id)->first();
        $station_id = $organization->station_id ?? null;

        $dtrs = Dtr::where('user_id', $user_id)
            ->whereBetween('date', [$start, $end])
            ->get();

        $dtrsByDate = $dtrs->keyBy('date');

        // reused overlap check: schedule/request-date range overlaps the month, including ranges that span across it
        $dateOverlap = function ($q) use ($start, $end) {
            $q->whereBetween('start', [$start, $end])
            ->orWhereBetween('end', [$start, $end])
            ->orWhere(function ($q2) use ($start, $end) {
                $q2->where('start', '<', $start)->where('end', '>', $end);
            });
        };

        $expandRange = function ($schedules, array &$dates) use ($start, $end) {
            foreach ($schedules as $schedule) {
                $from = Carbon::parse($schedule->start)->max($start);
                $to   = Carbon::parse($schedule->end ?? $schedule->start)->min($end);

                foreach (CarbonPeriod::create($from, $to) as $d) {
                    $dates[$d->toDateString()] = true;
                }
            }
        };

        // HOLIDAYS, scoped to the user's station via schedule_stations
        $holidayDates = [];
        $expandRange(
            Schedule::where('event_id', 1)
                ->when($station_id, fn($q) => $q->whereHas('stations', fn($q2) => $q2->where('station_id', $station_id)))
                ->where($dateOverlap)
                ->get(['start', 'end']),
            $holidayDates
        );

        // WORK SUSPENSIONS, scoped to the user's station via schedule_stations
        $suspensionDates = [];
        $expandRange(
            Schedule::where('event_id', 2)
                ->when($station_id, fn($q) => $q->whereHas('stations', fn($q2) => $q2->where('station_id', $station_id)))
                ->where($dateOverlap)
                ->get(['start', 'end']),
            $suspensionDates
        );

        // INCOMPLETE: any of the 4 punch columns is empty/null, excluding halfdays and station-suspended dates
        $incomplete = $dtrs->filter(function ($dtr) use ($suspensionDates) {
            if ($dtr->is_halfday == 1) return false;
            if (isset($suspensionDates[$dtr->date])) return false;

            return $dtr->am_in_at === null || $dtr->am_out_at === null || $dtr->pm_in_at === null || $dtr->pm_out_at === null;
        })->count();

        // LATE: tardiness or undertime minutes not equal to zero, excluding station-suspended dates
        $late = $dtrs->filter(function ($dtr) use ($suspensionDates) {
            if (isset($suspensionDates[$dtr->date])) return false;

            return $dtr->tardiness > 0 || $dtr->undertime > 0;
        })->count();

        // OFFICIAL TRAVEL
        $travelDates = [];
        $travels = Request::where('type_id', 156)
            ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
            ->whereHas('dates', $dateOverlap)
            ->with('dates')
            ->get();

        foreach ($travels as $travel) {
            foreach ($travel->dates as $date) {
                $from = Carbon::parse($date->start)->max($start);
                $to   = Carbon::parse($date->end ?? $date->start)->min($end);

                foreach (CarbonPeriod::create($from, $to) as $d) {
                    $travelDates[$d->toDateString()] = true;
                }
            }
        }

        // OFFICIAL BUSINESS
        $businessDates = [];
        $obs = Request::where('type_id', 192)
            ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
            ->whereHas('dates', $dateOverlap)
            ->with('dates')
            ->get();

        foreach ($obs as $ob) {
            foreach ($ob->dates as $date) {
                $from = Carbon::parse($date->start)->max($start);
                $to   = Carbon::parse($date->end ?? $date->start)->min($end);

                foreach (CarbonPeriod::create($from, $to) as $d) {
                    $businessDates[$d->toDateString()] = true;
                }
            }
        }

        // WORKING DAYS: based on the user's shift (shift_times.days), falling back to Mon-Fri if no shift is set
        $workingDays = collect();
        if ($organization && $organization->shift) {
            $workingDays = $organization->shift->times
                ->pluck('days')
                ->flatMap(fn($days) => explode(',', $days))
                ->map(fn($d) => (int) $d)
                ->unique();
        }

        $absences = 0;
        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            $dateStr = $date->toDateString();

            // skip future days (still in month)
            if ($date->greaterThan(now())) continue;

            // skip non-working days per the user's shift schedule
            $isWorkingDay = $workingDays->isNotEmpty()
                ? $workingDays->contains((int) $date->format('N'))
                : !$date->isWeekend();
            if (!$isWorkingDay) continue;

            // skip holidays / suspensions
            if (isset($holidayDates[$dateStr])) continue;
            if (isset($suspensionDates[$dateStr])) continue;

            // skip travel / official business
            if (isset($travelDates[$dateStr])) continue;
            if (isset($businessDates[$dateStr])) continue;

            $dtr = $dtrsByDate->get($dateStr);

            if (!$dtr) {
                // ABSENT the whole day: no DTR record at all
                $absences += 1;
            } elseif ($dtr->is_halfday == 1) {
                // ABSENT half a day
                $absences += 0.5;
            }
        }

        return [
            $incomplete,
            $late,
            $absences
        ];
    }

    public function whereabouts(){
        $today = Carbon::today();
        $date = $today->toDateString();

        $users = User::whereHas('profile')
            ->with([
                'profile:id,user_id,firstname,middlename,lastname,suffix_id,avatar',
                'profile.suffix:id,name',
                'organization:id,user_id,station_id,shift_id',
                'organization.station:id,name',
                'organization.shift.times',
                'dtrs' => function ($q) use ($date) {
                    $q->whereDate('date', $date);
                }
            ])
            ->whereHas('organization', function ($query) {
                $query->where('status_id',2);
            })
            ->get();

        // reused overlap check: a schedule/request-date range that covers today
        $dateOverlap = function ($q) use ($date) {
            $q->whereDate('start', '<=', $date)->whereDate('end', '>=', $date);
        };

        // stations affected by an event today, split into station-specific vs station-agnostic (applies to everyone)
        $mapStations = function ($schedules) {
            $ids = [];
            $any = $schedules->isNotEmpty();

            foreach ($schedules as $schedule) {
                foreach ($schedule->stations as $pivot) {
                    if ($pivot->station) {
                        $ids[$pivot->station_id] = $pivot->station->name;
                    }
                }
            }

            return ['ids' => $ids, 'any' => $any];
        };

        // HOLIDAYS today, with the stations they apply to
        $holidays = $mapStations(
            Schedule::where('event_id', 1)->where($dateOverlap)->with('stations.station:id,name')->get(['id', 'start', 'end'])
        );

        // WORK SUSPENSIONS today, with the stations they apply to
        $suspensions = $mapStations(
            Schedule::where('event_id', 2)->where($dateOverlap)->with('stations.station:id,name')->get(['id', 'start', 'end'])
        );

        $travels = Request::where('type_id', 156)
            ->whereHas('dates', $dateOverlap)
            ->with('tags')
            ->get()
            ->pluck('tags.*.user_id')
            ->flatten()
            ->unique()
            ->flip();

        $business = Request::where('type_id', 192)
            ->whereHas('dates', $dateOverlap)
            ->with('tags')
            ->get()
            ->pluck('tags.*.user_id')
            ->flatten()
            ->unique()
            ->flip();

        $result = [];

        foreach ($users as $user) {

            $stationId = $user->organization->station_id ?? null;
            $stationName = $user->organization->station->name ?? null;
            $hasDtr = $user->dtrs->isNotEmpty();
            $station = null;

            // a station-suspension/holiday only applies to users assigned to that station;
            // users with no assigned station fall under any station-agnostic event for the day
            if ($stationId ? isset($suspensions['ids'][$stationId]) : $suspensions['any']) {
                $status = 'Suspension';
                $station = $stationName;
            }
            elseif ($stationId ? isset($holidays['ids'][$stationId]) : $holidays['any']) {
                $status = 'Holiday';
                $station = $stationName;
            }
            elseif ($travels->has($user->id) || $business->has($user->id)) {
                $status = 'OB/On Travel';
            }
            elseif ($hasDtr) {
                $status = 'Present';
            }
            elseif (!$this->isWorkingDay($user->organization, $today)) {
                // not required to work today per their shift schedule - don't count as absent
                continue;
            }
            else {
                $status = 'Absent';
            }

            $result[] = [
                'user_id' => $user->id,
                'name' => $user->profile->fullname,
                'avatar' => $user->profile?->avatar,
                'status' => $status,
                'station' => $station,
            ];
        }

        return $result;
    }

    // whether $date is a working day per the user's shift schedule (shift_times.days), falling back to Mon-Fri if no shift is set
    private function isWorkingDay($organization, Carbon $date): bool
    {
        $workingDays = collect();

        if ($organization && $organization->shift) {
            $workingDays = $organization->shift->times
                ->pluck('days')
                ->flatMap(fn($days) => explode(',', $days))
                ->map(fn($d) => (int) $d)
                ->unique();
        }

        return $workingDays->isNotEmpty()
            ? $workingDays->contains((int) $date->format('N'))
            : !$date->isWeekend();
    }
}
