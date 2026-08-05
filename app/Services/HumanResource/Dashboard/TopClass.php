<?php

namespace App\Services\HumanResource\Dashboard;

use App\Models\Dtr;
use App\Models\User;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Http\Resources\DefaultResource;

class TopClass
{
    public function absences($request)
    {
        [$startOfMonth, $endOfMonth] = $this->monthRange($request);
        $today = Carbon::today();
        $scheduledDays = $this->scheduledDays($startOfMonth, $endOfMonth);

        $users = User::with(['profile', 'organization.shift.times', 'dtrs' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }])
            ->whereHas('organization', function ($q) {
                $q->where('type_id', 16)->where('status_id', 2);
            })
            ->get();

        // Compute absences: no DTR entry on a working day = 1 absence, is_halfday = 0.5 absence
        $usersWithAbsences = $users->map(function ($user) use ($startOfMonth, $endOfMonth, $today, $scheduledDays) {
            $absentCount = $this->userAbsenceCount($user, $startOfMonth, $endOfMonth, $today, $scheduledDays);

            if ($absentCount > 0) {
                $user->absences_count = $absentCount;
                return $user;
            }
        })
        ->filter()
        ->sortByDesc('absences_count')
        ->take(10)
        ->values();

        return $usersWithAbsences;
    }

    public function lates($request)
    {
        [$startOfMonth, $endOfMonth] = $this->monthRange($request);

        // Users (Regular type, Active status only)
        $users = User::with('profile')
            ->whereHas('organization', function ($q) {
                $q->where('type_id', 16)->where('status_id', 2);
            })
            ->get();

        // Compute lates: DTR tardiness/undertime not zero, only counting completed DTRs
        $usersWithLates = $users->map(function ($user) use ($startOfMonth, $endOfMonth) {
            $lateCount = $user->dtrs()
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('is_completed', 1)
                ->where(function ($q) {
                    $q->where('tardiness', '!=', 0)
                    ->orWhere('undertime', '!=', 0);
                })
                ->count(); // 1 per DTR

            if ($lateCount > 0) {
                $user->lates_count = $lateCount;
                return $user;
            }
        })
        ->filter()
        ->sortByDesc('lates_count')
        ->take(10)
        ->values();

        return $usersWithLates;
    }

    public function tardinessReport($request)
    {
        [$startOfMonth, $endOfMonth] = $this->monthRange($request);

        // Users (Regular type, Active status only) with monthly undertime/tardiness totals
        // (only completed DTRs count toward tardiness/undertime; incomplete ones are tracked separately below)
        $users = User::with(['profile', 'organization.division'])
            ->whereHas('organization', function ($q) {
                $q->where('type_id', 16)->where('status_id', 2);
            })
            ->withSum(['dtrs as undertime_minutes' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth])->where('is_completed', 1);
            }], 'undertime')
            ->withSum(['dtrs as tardiness_minutes' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth])->where('is_completed', 1);
            }], 'tardiness')
            ->withCount(['dtrs as occurrences' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->where('is_completed', 1)
                    ->where(function ($q2) {
                        $q2->where('tardiness', '!=', 0)
                        ->orWhere('undertime', '!=', 0);
                    });
            }])
            ->withCount(['dtrs as incomplete_count' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->where('is_completed', 0);
            }])
            ->get();

        $groups = $users
            ->map(function ($user) {
                $undertime = (int) $user->undertime_minutes;
                $tardiness = (int) $user->tardiness_minutes;

                return [
                    'user_id' => $user->id,
                    'name' => $user->profile->name ?? '',
                    'division' => $user->organization->division->name ?? 'Unassigned',
                    'undertime' => $undertime,
                    'tardiness' => $tardiness,
                    'total' => $undertime + $tardiness,
                    'occurrences' => (int) $user->occurrences,
                    'incomplete_count' => (int) $user->incomplete_count,
                ];
            })
            ->groupBy('division')
            ->sortKeys()
            ->map(function ($users, $division) {
                return [
                    'division' => $division,
                    'users' => $users->sortBy([
                        ['occurrences', 'desc'],
                        ['total', 'desc'],
                    ])->values(),
                ];
            })
            ->values();

        // DTRs excluded from the totals above because they are not yet completed
        $incompleteList = Dtr::with(['user.profile', 'user.organization.division'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('is_completed', 0)
            ->whereHas('user.organization', function ($q) {
                $q->where('type_id', 16)->where('status_id', 2);
            })
            ->orderBy('date')
            ->get()
            ->map(function ($dtr) {
                return [
                    'user_id' => $dtr->user_id,
                    'name' => $dtr->user->profile->name ?? '',
                    'division' => $dtr->user->organization->division->name ?? 'Unassigned',
                    'date' => $dtr->date,
                ];
            })
            ->values();

        return [
            'groups' => $groups,
            'incomplete_count' => $incompleteList->count(),
            'incomplete' => $incompleteList,
        ];
    }

    public function absencesReport($request)
    {
        [$startOfMonth, $endOfMonth] = $this->monthRange($request);
        $today = Carbon::today();
        $scheduledDays = $this->scheduledDays($startOfMonth, $endOfMonth);

        // Users (Regular type, Active status only) with monthly absence totals
        $users = User::with(['profile', 'organization.shift.times', 'organization.division', 'dtrs' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }])
            ->whereHas('organization', function ($q) {
                $q->where('type_id', 16)->where('status_id', 2);
            })
            ->get();

        return $users
            ->map(function ($user) use ($startOfMonth, $endOfMonth, $today, $scheduledDays) {
                $absences = $this->userAbsenceCount($user, $startOfMonth, $endOfMonth, $today, $scheduledDays);

                return [
                    'user_id' => $user->id,
                    'name' => $user->profile->name ?? '',
                    'division' => $user->organization->division->name ?? 'Unassigned',
                    'absences' => $absences,
                    'total' => $absences,
                ];
            })
            ->groupBy('division')
            ->sortKeys()
            ->map(function ($users, $division) {
                return [
                    'division' => $division,
                    'users' => $users->sortBy([
                        ['absences', 'desc'],
                        ['name', 'asc'],
                    ])->values(),
                ];
            })
            ->values();
    }

    // Carbon start/end of the requested (or current) month
    private function monthRange($request)
    {
        $year  = $request->year ?? date('Y');
        $monthName = $request->month ?? date('F');
        $month = date('m', strtotime($monthName));

        return [
            Carbon::create($year, $month, 1)->startOfMonth(),
            Carbon::create($year, $month, 1)->endOfMonth(),
        ];
    }

    // dates covered by any Schedule (holiday/suspension/etc.) within the range, flipped for isset() lookups
    private function scheduledDays($startOfMonth, $endOfMonth)
    {
        $schedules = Schedule::where(function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('start', [$startOfMonth, $endOfMonth])
            ->orWhereBetween('end', [$startOfMonth, $endOfMonth])
            ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->where('start', '<=', $startOfMonth)
                    ->where('end', '>=', $endOfMonth);
            });
        })->get();

        $scheduledDays = collect();

        foreach ($schedules as $schedule) {
            $start = Carbon::parse($schedule->start);
            $end   = $schedule->end
                ? Carbon::parse($schedule->end)
                : $start;

            $scheduledDays = $scheduledDays->merge(
                collect(CarbonPeriod::create($start, $end))
                    ->map(fn ($d) => $d->toDateString())
            );
        }

        return $scheduledDays->flip();
    }

    // no DTR entry on a working day = 1 absence, is_halfday = 0.5 absence
    private function userAbsenceCount($user, $startOfMonth, $endOfMonth, $today, $scheduledDays)
    {
        // working days per the user's shift schedule, falling back to Mon-Fri if no shift is set
        $workingDays = collect();
        if ($user->organization && $user->organization->shift) {
            $workingDays = $user->organization->shift->times
                ->pluck('days')
                ->flatMap(fn ($days) => explode(',', $days))
                ->map(fn ($d) => (int) $d)
                ->unique();
        }

        $dtrsByDate = $user->dtrs->keyBy('date');
        $absentCount = 0;

        foreach (CarbonPeriod::create($startOfMonth, $endOfMonth) as $date) {
            if ($date->greaterThan($today)) {
                continue;
            }

            $isWorkingDay = $workingDays->isNotEmpty()
                ? $workingDays->contains((int) $date->format('N'))
                : !$date->isWeekend();
            if (!$isWorkingDay) {
                continue;
            }

            $dateStr = $date->toDateString();
            if (isset($scheduledDays[$dateStr])) {
                continue;
            }

            $dtr = $dtrsByDate->get($dateStr);

            if (!$dtr) {
                // no entry / not in the office: absent the whole day
                $absentCount += 1;
            } elseif ($dtr->is_halfday == 1) {
                // half day
                $absentCount += 0.5;
            }
        }

        return $absentCount;
    }
}
