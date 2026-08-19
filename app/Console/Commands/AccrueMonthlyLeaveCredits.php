<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\ListLeave;
use App\Models\UserCredit;
use App\Models\Schedule;
use App\Models\Request;
use App\Models\OrgChart;
use Illuminate\Console\Command;

/**
 * Posts one month's worth of CSC-prorated leave credit, once that month is
 * fully over, using actual attendance instead of assuming a full month.
 *
 * Modeling choices worth reviewing against your agency's actual rules:
 * - "Days rendered" is counted against working days (per shift, or Mon-Fri
 *   if unset), not raw calendar days — mirrors TopClass::userAbsenceCount().
 * - A filed leave request (type_id 158) covering a date excludes it from the
 *   absence count regardless of its approval status, and its `nopay` days
 *   aren't separately subtracted — mirrors the existing precedent in
 *   Payroll\ContractualClass::tardiness(). A request with unpaid (`nopay`)
 *   days therefore isn't currently docked here.
 * - Holidays/suspensions are any Schedule row with event_id 1 or 2,
 *   regardless of station (ContractualClass also filters by station; that
 *   finer filter was dropped here for simplicity).
 */
class AccrueMonthlyLeaveCredits extends Command
{
    protected $signature = 'credits:accrue-monthly {--month= : Target month as Y-m (e.g. 2026-07). Defaults to last month}';
    protected $description = 'Post the just-completed month\'s CSC-prorated leave credit (Vacation/Sick) to active regular employees, based on actual attendance';

    public function handle()
    {
        $target = $this->option('month')
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth()
            : Carbon::today()->subMonthNoOverflow()->startOfMonth();

        $startOfMonth = $target->copy()->startOfMonth();
        $endOfMonth = $target->copy()->endOfMonth();

        $leaves = ListLeave::where('is_active', 1)
            ->where('is_requested', 0)
            ->where('renewal_period', 'monthly')
            ->get();

        if ($leaves->isEmpty()) {
            $this->info('No monthly-accrual leave types configured.');
            return self::SUCCESS;
        }

        $holidays = $this->holidaysWithin($startOfMonth, $endOfMonth);

        $users = User::with(['organization.shift.times', 'dtrs' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
            }])
            ->whereHas('organization', function ($query) {
                $query->where('type_id', 15)->where('status_id', 2);
            })
            ->get();

        $monthsCompleted = $target->month - 1;
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            [$totalWorkingDays, $absentDays] = $this->attendanceFor($user, $startOfMonth, $endOfMonth, $holidays);

            $daysWithPay = $totalWorkingDays - $absentDays;
            $nominalDay = $totalWorkingDays > 0
                ? min(30, round(($daysWithPay / $totalWorkingDays) * 30))
                : 0;

            foreach ($leaves as $leave) {
                $ytdEarned = round(($monthsCompleted * $leave->accrual_rate) + ($nominalDay * $leave->accrual_rate / 30), 3);

                $credit = UserCredit::firstOrCreate(
                    ['user_id' => $user->id, 'leave_id' => $leave->id, 'year' => $target->year],
                    ['balance' => 0, 'earned' => 0, 'used' => 0]
                );

                $delta = round($ytdEarned - $credit->earned, 3);
                if ($delta <= 0) {
                    continue;
                }

                $oldBalance = $credit->balance;
                $credit->earned = $ytdEarned;
                $credit->balance = round($oldBalance + $delta, 3);
                $credit->save();

                $credit->logs()->create([
                    'amount' => $delta,
                    'old_balance' => $oldBalance,
                    'new_balance' => $credit->balance,
                    'remarks' => 'Accrual for ' . $target->format('F Y') . " ({$daysWithPay}/{$totalWorkingDays} working days with pay)",
                    'user_id' => 1,
                    'type_id' => 162,
                ]);
            }

            $bar->advance();
        }
        $bar->finish();

        $this->newLine(2);
        $this->info("Monthly leave credit accrual complete for {$target->format('F Y')}.");

        return self::SUCCESS;
    }

    /** @return array{0: float, 1: float} [totalWorkingDays, absentDays] */
    private function attendanceFor(User $user, Carbon $startOfMonth, Carbon $endOfMonth, \Illuminate\Support\Collection $holidays): array
    {
        $exempt = in_array($user->id, OrgChart::excludedFromAttendance());

        $workingDays = collect();
        if ($user->organization && $user->organization->shift) {
            $workingDays = $user->organization->shift->times
                ->pluck('days')
                ->flatMap(fn ($days) => explode(',', $days))
                ->map(fn ($d) => (int) trim($d))
                ->unique();
        }

        // exempt users aren't required to log DTR, so don't clip to their hire date
        // the way non-exempt users are, or the check below would starve them of days
        $periodStart = (!$exempt && $user->created_at)
            ? $startOfMonth->copy()->max(Carbon::parse($user->created_at)->startOfDay())
            : $startOfMonth;

        $leaveDates = $this->leaveDatesFor($user->id, $startOfMonth, $endOfMonth);
        $dtrsByDate = $user->dtrs->keyBy('date');

        $totalWorkingDays = 0;
        $absentDays = 0;

        foreach (CarbonPeriod::create($periodStart, $endOfMonth) as $date) {
            $isWorkingDay = $workingDays->isNotEmpty()
                ? $workingDays->contains((int) $date->format('N'))
                : !$date->isWeekend();
            if (!$isWorkingDay) {
                continue;
            }

            $dateStr = $date->toDateString();
            if (isset($holidays[$dateStr])) {
                continue;
            }

            $totalWorkingDays++;

            // exempt from attendance checking: treat every working day as rendered, no DTR needed
            if ($exempt || isset($leaveDates[$dateStr])) {
                continue;
            }

            $dtr = $dtrsByDate->get($dateStr);
            if (!$dtr) {
                $absentDays += 1;
            } elseif ($dtr->is_halfday == 1) {
                $absentDays += 0.5;
            }
        }

        return [$totalWorkingDays, $absentDays];
    }

    // Schedule rows flagged as a holiday/suspension (event_id 1 or 2)
    private function holidaysWithin(Carbon $startOfMonth, Carbon $endOfMonth): \Illuminate\Support\Collection
    {
        $schedules = Schedule::whereIn('event_id', [1, 2])
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('start', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) {
                        $q2->where('start', '<=', $startOfMonth)->where('end', '>=', $endOfMonth);
                    });
            })
            ->get();

        $days = collect();
        foreach ($schedules as $schedule) {
            $start = Carbon::parse($schedule->getRawOriginal('start'));
            $end = $schedule->end ? Carbon::parse($schedule->getRawOriginal('end')) : $start;
            $days = $days->merge(collect(CarbonPeriod::create($start, $end))->map(fn ($d) => $d->toDateString()));
        }

        return $days->flip();
    }

    // dates covered by any filed leave request (type_id 158) for this user, within range
    private function leaveDatesFor(int $userId, Carbon $startOfMonth, Carbon $endOfMonth): \Illuminate\Support\Collection
    {
        $requests = Request::where('type_id', 158)
            ->where('user_id', $userId)
            ->with('dates')
            ->whereHas('dates', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->where('start', '<=', $endOfMonth->toDateString())
                    ->where('end', '>=', $startOfMonth->toDateString());
            })
            ->get();

        $days = collect();
        foreach ($requests as $request) {
            foreach ($request->dates as $date) {
                $start = Carbon::parse($date->getRawOriginal('start'));
                $end = Carbon::parse($date->getRawOriginal('end'));
                $days = $days->merge(collect(CarbonPeriod::create($start, $end))->map(fn ($d) => $d->toDateString()));
            }
        }

        return $days->flip();
    }
}
