<?php

namespace App\Services\HumanResource\Payroll;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Hashids\Hashids;
use App\Models\Dtr;
use App\Models\User;
use App\Models\UserDeduction;
use App\Models\UserOrganization;
use App\Models\Schedule;
use App\Models\Request;
use App\Models\Payroll;
use App\Models\PayrollCycle;
use App\Models\PayrollCutoff;
use App\Models\PayrollDeduction;
use App\Http\Resources\HumanResource\Dtr\TimeResource;
use App\Http\Resources\HumanResource\Payroll\ListResource;
use App\Http\Resources\HumanResource\Payroll\Contractual\CycleResource;
use App\Http\Resources\HumanResource\Payroll\Contractual\CutoffResource;

class ContractualClass
{
    public function __construct()
    {
        $this->holidays = Schedule::pluck('start')
        ->map(function ($date) {
            return Carbon::parse($date)->toDateString();
        })->toArray();
    }

    public function lists($request){
        $data = ListResource::collection(
            PayrollCutoff::with('cycle','status')
            ->with('user:id,username','user.profile:id,user_id,firstname,middlename,lastname,suffix_id')
            // ->with('payrolls.deductions.deduction')
            // ->with('payrolls.user.profile:id,user_id,firstname,middlename,lastname,suffix_id')
            // ->with('payrolls.user:id,username','payrolls.user.organization:id,user_id,position_id,salary_id,type_id','payrolls.user.organization.position:id,name','payrolls.user.organization.type:id,name','payrolls.user.organization.salary:id,grade,amount')
            ->withSum('payrolls as total', 'netpay')
            ->withSum('payrolls as deduction', 'deduction')
            ->withSum('payrolls as compensation', 'gross')
            ->withCount('payrolls as count')
            ->whereHas('cycle', function ($query) {
                $query->where('is_regular',0);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = new CutoffResource(
            PayrollCutoff::query()
            ->with('cycle','status')
            ->with('user:id,username','user.profile:id,user_id,firstname,middlename,lastname,suffix_id')
            ->with('payrolls.deductions.deduction')
            ->with('payrolls.user.profile:id,user_id,firstname,middlename,lastname,suffix_id')
            ->with('payrolls.user:id,username','payrolls.user.organization:id,user_id,position_id,salary_id,type_id','payrolls.user.organization.type:id,name','payrolls.user.organization.position:id,name','payrolls.user.organization.salary:id,grade,amount','payrolls.user.deductions.deduction')
            ->withSum('payrolls as total', 'netpay')
            ->withCount('payrolls as count')
            ->where('id',$id)->first()
        );
        return $data;
    }

    public function cycle($request){
        $year = $request->year;
        $month = $request->month;

        $cycle = PayrollCycle::where('month',$month)->where('year',$year)->where('is_regular',0)->first();
        if($cycle){
            $batch = PayrollCutoff::where('type',$request->type)->where('cycle_id',$cycle->id)->count();
            $data = PayrollCutoff::create(
                array_merge($request->all(), [
                    'code' => $this->generateCode2(),
                    'user_id' => \Auth::user()->id,
                    'batch' => $batch + 1,
                    'cycle_id' => $cycle->id,
                    'status_id' => 17
                ])
            );
        }else{
            $data = PayrollCycle::create(array_merge($request->all(), [
                'code' => $this->generateCode(),
                'user_id' => \Auth::user()->id
            ]));
            $cutoff = $data->cutoffs()->create(
                array_merge($request->all(), [
                    'code' => $this->generateCode2(),
                    'user_id' => \Auth::user()->id,
                    'batch' => 1,
                    'status_id' => 17
                ])
            );
        }
        return [
            'data' => new CycleResource($data),
            'message' => 'Cycle creation was successful!', 
            'info' => "You've successfully created a new cycle."
        ];
    }

    public function search($request){
        $keyword = $request->keyword;
        $cutoff_id = $request->cutoff_id;
        $is_regular = $request->is_regular;
        $start = \Carbon\Carbon::parse($request->start)->startOfDay();
        $end = \Carbon\Carbon::parse($request->end)->endOfDay();

        $data =  User::with([
            'profile',
            'profile.suffix',
            'profile.sex',
            'organization.shift.times',
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
        ->when(is_null($is_regular) || $is_regular == 0, function ($query) {
            $query->whereHas('organization', function ($query) {
                $query->where('type_id', '!=', 15);
            });
        })
        ->when($keyword, function ($query) use ($keyword){
            $query->whereHas('profile', function ($q) use ($keyword) {
                $q->where('lastname', 'like', '%' . $keyword . '%');
            });
        })
        ->limit(300)->get()->map(function ($item) use ($start, $end){
            $alreadyInPayroll = $item->payrolls->isNotEmpty();
            $summary = $this->buildDtrSummary($item, $start, $end);

            return array_merge([
                'value' => $item->id,
                'name' => $item->profile->name,
                'fullname' => $item->profile->fullname,
                'sex' => optional($item->profile->sex)->name,
                'position' => optional($item->organization->position)->name,
                'division' => optional($item->organization->division)->name,
                'division_id' => optional($item->organization->division)->id,
                'type' => optional($item->organization->type)->name,
                'avatar' => $this->avatarUrl($item->profile),
                'already_in_payroll' => $alreadyInPayroll,
                'dtrs' => $alreadyInPayroll ? [] : $summary['dtrs']
            ], [
                'completed_count' => $summary['completed_count'],
                'total_work_days' => $summary['total_work_days'],
                'holiday_count' => $summary['holiday_count'],
                'leave_count' => $summary['leave_count'],
                'travel_count' => $summary['travel_count'],
                'business_count' => $summary['business_count'],
                'absent_count' => $summary['absent_count'],
                'is_complete' => $summary['is_complete']
            ]);
        })->sortBy('name')->values();
        return $data;
    }

    private function avatarUrl($profile){
        $avatar = optional($profile)->avatar;
        if (!$avatar || $avatar === 'noavatar.jpg') {
            return asset('images/avatars/avatar.jpg');
        }
        return (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://'))
            ? $avatar
            : asset('storage/' . $avatar);
    }

    /**
     * Computes the daily DTR breakdown and completion counts for a single
     * employee within a date range, treating holidays/travel/business/leave
     * days as excluded from the required-attendance total.
     */
    private function buildDtrSummary($item, $start, $end){
        $user_id = $item->id;
        $station_id = optional($item->organization)->station_id;
        $period = \Carbon\CarbonPeriod::create($start, $end);

        /**
         * =========================
         *  HOLIDAYS
         * =========================
         */
        $holidays = Schedule::where(function ($q) use ($start, $end) {
            $q->whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('start', '<', $start)
                        ->where('end', '>', $end);
                });
        })
        ->whereHas('stations', function ($q) use ($station_id) {
            $q->where('station_id', $station_id);
        })
        ->get(['start', 'end', 'title'])
        ->flatMap(function ($holiday) {
            $list = [];
            $startDate = \Carbon\Carbon::parse($holiday->start);
            $endDate = \Carbon\Carbon::parse($holiday->end ?? $holiday->start);

            foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $day) {
                $list[$day->format('Y-m-d')] = $holiday->title;
            }

            return $list;
        });

        /**
         * =========================
         *  OFFICIAL TRAVEL
         * =========================
         */
        $officialTravel = [];
        $travels = Request::where('type_id', 156)
            ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
            ->whereHas('dates', function ($q) use ($start, $end) {
                $q->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start', '<', $start)
                            ->where('end', '>', $end);
                    });
            })
            ->with('dates', 'detail', 'location', 'location.municipality')
            ->get();

        foreach ($travels as $travel) {
            foreach ($travel->dates as $travelDate) {
                $period2 = \Carbon\CarbonPeriod::create($travelDate->start, $travelDate->end ?? $travelDate->start);
                foreach ($period2 as $day) {
                    $address = optional($travel->location)->address;
                    $municipality = optional(optional($travel->location)->municipality)->name;
                    $officialTravel[$day->format('Y-m-d')] = ($address || $municipality)
                        ? trim(implode(', ', array_filter([$address, $municipality])))
                        : 'Official Travel';
                }
            }
        }
        /**
         * =========================
         *  OFFICIAL BUSINESS
         * =========================
         */
        $officialBusiness = [];
        $obs = Request::where('type_id', 192)
            ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
            ->withWhereHas('dates', function ($q) use ($start, $end) {
                $q->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start', '<', $start)
                            ->where('end', '>', $end);
                    });
            })
            ->with('event')
            ->get();

        foreach ($obs as $ob) {
            foreach ($ob->dates as $obDate) {
                $period3 = \Carbon\CarbonPeriod::create($obDate->start, $obDate->end ?? $obDate->start);
                foreach ($period3 as $day) {
                    $officialBusiness[$day->format('Y-m-d')] = optional($ob->event)->title ?? 'Official Business';
                }
            }
        }

        /**
         * =========================
         *  OFFICIAL LEAVE
         * =========================
         */
        $officialLeave = [];
        $leaves = Request::where('type_id', 158)
            ->whereHas('tags', fn($q) => $q->where('user_id', $user_id))
            ->withWhereHas('dates', function ($q) use ($start, $end) {
                $q->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start', '<', $start)
                            ->where('end', '>', $end);
                    });
            })
            ->with('leave.type')
            ->get();

        foreach ($leaves as $ob) {
            foreach ($ob->dates as $obDate) {
                $period3 = \Carbon\CarbonPeriod::create($obDate->start, $obDate->end ?? $obDate->start);
                foreach ($period3 as $day) {
                    $officialLeave[$day->format('Y-m-d')] = optional(optional($ob->leave)->type)->name ?? 'Leave';
                }
            }
        }

        $uniqueDays = optional(optional($item->organization)->shift)->times
            ? $item->organization->shift->times->pluck('days')
                ->flatMap(function ($days) {
                    return explode(',', $days);
                })->unique()->values()
            : collect();

        // Generate daily data
        $dates = [];
        foreach ($period as $date) {
            $dateStr = $date->toDateString();

            $status = null;
            $title = null;

            $dayNumber = (int) $date->format('N');
            if (isset($holidays[$dateStr])) {
                $status = 'Holiday';
                $title = $holidays[$dateStr];
            }
            elseif (!$uniqueDays->contains($dayNumber)) {
                $status = 'Non-working Day';
                $title = 'Non-working Day';
            }
            elseif (isset($officialTravel[$dateStr])) {
                $status = 'Official Travel';
                $title = $officialTravel[$dateStr];
            }elseif (isset($officialLeave[$dateStr])) {
                $status = 'Official Leave';
                $title = $officialLeave[$dateStr];
            } elseif (isset($officialBusiness[$dateStr])) {
                $status = 'Official Business';
                $title = $officialBusiness[$dateStr];
            }

            $day = Carbon::parse($dateStr)->dayOfWeekIso; // 1-7

            if (! $uniqueDays->contains($day)) {
                continue;
            }

            $isAttendanceRequired = !in_array($status, [
                'Holiday',
                'Official Travel',
                'Official Business',
            ]);

            $dtr = $item->dtrs->firstWhere('date', $dateStr);

            $dates[] = [
                'date' => Carbon::parse($dateStr)->format('F d, Y'),
                'date_day' => Carbon::parse($dateStr)->format('l'),
                'am_in' => ($dtr && $dtr->am_in_at) ? new TimeResource(json_decode($dtr->am_in_at)) : null,
                'am_out' => ($dtr && $dtr->am_out_at) ? new TimeResource(json_decode($dtr->am_out_at)) : null,
                'pm_in'  => ($dtr && $dtr->pm_in_at)  ? new TimeResource(json_decode($dtr->pm_in_at))  : null,
                'pm_out' => ($dtr && $dtr->pm_out_at) ? new TimeResource(json_decode($dtr->pm_out_at)) : null,
                'is_completed' => $isAttendanceRequired ? ($dtr?->is_completed) : null,
                'status' => $status ?? ($dtr ? 'Present' : 'Absent'),
                'title' => $title
            ];
        }

        $holidayCount = collect($dates)->where('status', 'Holiday')->count();
        $leaveCount = collect($dates)->where('status', 'Official Leave')->count();
        $travelCount = collect($dates)->where('status', 'Official Travel')->count();
        $businessCount = collect($dates)->where('status', 'Official Business')->count();
        $absentCount = collect($dates)->where('status', 'Absent')->count();
        $completedCount = collect($dates)->where('is_completed', 1)->count();
        $totalWorkDays = count($dates) - ($holidayCount + $travelCount + $absentCount + $businessCount + $leaveCount);

        return [
            'dtrs' => $dates,
            'holiday_count' => $holidayCount,
            'leave_count' => $leaveCount,
            'travel_count' => $travelCount,
            'business_count' => $businessCount,
            'absent_count' => $absentCount,
            'completed_count' => $completedCount,
            'total_work_days' => $totalWorkDays,
            'is_complete' => $totalWorkDays > 0 && $completedCount == $totalWorkDays
        ];
    }

    public function payroll($request){

        $data = PayrollCutoff::with('cycle')->where('id', $request->id)->first();
        $start = Carbon::parse($data->start)->startOfDay();
        $end = Carbon::parse($data->end)->endOfDay();

        $userIds = $request->filled('user_ids') ? (array) $request->user_ids : array_filter([$request->user_id]);
        $userIds = array_values(array_unique($userIds));

        $added = [];
        $skipped = [];

        foreach ($userIds as $user) {
            $exist = Payroll::where('user_id', $user)->where('cutoff_id', $request->id)->first();
            if ($exist) {
                $skipped[] = $user;
                continue;
            }

            $employee = User::with(['organization.shift.times', 'dtrs' => function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end]);
            }])->find($user);

            if (!$employee) {
                $skipped[] = $user;
                continue;
            }

            $summary = $this->buildDtrSummary($employee, $start, $end);
            if (!$summary['is_complete']) {
                $skipped[] = $user;
                continue;
            }

            $payroll = $data->payrolls()->create([
                'user_id' => $user,
                'cutoff_id' => $request->id
            ]);

            if ($payroll) {
                $added[] = $user;
                $salary = floatval(str_replace(['₱', ','], '', optional(UserOrganization::with('salary')->where('user_id', $user)->first())->salary?->amount));
                if($data->type == '1st') {
           
                    $total = 0;
                    $deductions = UserDeduction::where('is_active', 1)->where('is_automatic', 1)->where('user_id', $user)->get();
                    foreach ($deductions as $deduction) {
                        PayrollDeduction::create([
                            'amount' => $deduction->amount,
                            'deduction_id' => $deduction->deduction_id,
                            'payroll_id' => $payroll->id
                        ]);
                        $cleanAmount = floatval(str_replace(['₱', ','], '', $deduction->amount));
                        $total += $cleanAmount;
                    }
                    

                    $payroll->gross = $salary;
                    $payroll->deduction = $total;
                    $payroll->netpay = $salary - $total;

                    if (!$data->cycle->is_regular) {
                        $tardiness = $this->tardiness($data, $user, $salary);
                        $payroll->mins = $tardiness['mins'];
                        $payroll->days = $tardiness['days'];
                        $payroll->tardiness = $tardiness['total'];
                        $payroll->netpay = ($salary / 2) - ($tardiness['total'] + $total);
                    }

                    $payroll->save();

                }elseif($data->type == '2nd') {
                    $previous = Payroll::where('user_id', $user)
                        ->whereHas('cutoff', function ($query) use ($data) {
                            $query->where('cycle_id', $data->cycle_id);
                        })
                        ->first();

                    $tardiness = $this->tardiness($data, $user, $salary);
                    $previous_net = (floatval(str_replace(['₱', ','], '', $previous->gross)) / 2) - floatval(str_replace(['₱', ','], '', $previous->tardiness));
                    $tax = ($previous_net + (($salary / 2) - $tardiness['total'])) * 0.02;

                    $payroll->gross = $salary;
                    $payroll->deduction = $tax;
                    $payroll->mins = $tardiness['mins'];
                    $payroll->days = $tardiness['days'];
                    $payroll->tardiness = $tardiness['total'];
                    $payroll->netpay = (($salary / 2) - round($tardiness['total'],2)) - round($tax,2);
                    $payroll->save();

                    $deduction = UserDeduction::where('is_active', 1)->where('is_automatic', 0)->where('user_id', $user)->first();
            
                    PayrollDeduction::create([
                        'amount' => $tax,
                        'deduction_id' => $deduction->deduction_id,
                        'payroll_id' => $payroll->id
                    ]);

                }
            }
        }

        return [
            'data' => ['added' => $added, 'skipped' => $skipped],
            'message' => count($added) > 0 ? 'Employees added successfully!' : 'No employees were added.',
            'info' => count($skipped) > 0
                ? count($added) . ' added, ' . count($skipped) . ' skipped (already in payroll or incomplete DTR).'
                : count($added) . ' employee(s) added to the payroll.'
        ];
    }

    private function tardiness($data,$user,$salary){
        $start = Carbon::parse($data->start);
        $end = Carbon::parse($data->end);
        $employee = User::with('organization.shift.times')->findOrFail($user);
        $station_id = UserOrganization::where('user_id',$user)->value('station_id');

        $workingDays = $employee->organization->shift->times
        ->pluck('days')
        ->flatMap(function ($days) {
            return explode(',', $days);
        })
        ->map(fn ($day) => (int) trim($day))
        ->unique()
        ->values();


        $datesList = collect();

        $holidays = Schedule::whereHas('stations', function ($q) use ($station_id) {
            $q->where('station_id', $station_id);
        })
        ->whereBetween('start', [
            Carbon::parse($start)->startOfDay(),
            Carbon::parse($end)->endOfDay(),
        ])
        ->whereIn('event_id',[1,2])
        ->pluck('start')
         ->map(function ($date) {
            return Carbon::parse($date)->toDateString();
        })->toArray();
       

        $excusedDates = Request::whereIn('type_id', [158, 156, 192])
        ->whereHas('tags', fn($q) => $q->where('user_id', $user))
        ->whereHas('dates', function ($q) use ($start, $end) {
            $q->whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('start', '<', $start)
                        ->where('end', '>', $end);
                });
        })
        ->with('dates')
        ->get();

        foreach ($excusedDates as $ob) {
            foreach ($ob->dates as $obDate) {
                $period3 = \Carbon\CarbonPeriod::create($obDate->start, $obDate->end ?? $obDate->start);
                foreach ($period3 as $day) {
                    $datesList->push($day->format('Y-m-d'));
                }
            }
        }

        $datesList = $datesList->unique()->sort()->values();
     
        $ignoredDates = $datesList
        ->merge($holidays)
            ->unique()
            ->sort()
            ->values();


        $period = CarbonPeriod::create($start, $end);
        $filteredPeriod = collect($period)->reject(function ($date) use ($ignoredDates) {
            return in_array($date->toDateString(), $ignoredDates->toArray());
        });
       
        $lateMinutes = 0;
        $undertimeMinutes = 0;
        $absentDays = 0;

        $dtrs = Dtr::where('user_id',$user)
        ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
        ->get()
        ->keyBy(fn ($dtr) => Carbon::parse($dtr->date)->toDateString());
       
        

        foreach ($filteredPeriod as $day) {
            $dayNumber = $day->dayOfWeekIso;

            if (! $workingDays->contains($dayNumber)) {
                continue;
            }

            $dayString = $day->toDateString();
            $dtr = $dtrs[$dayString] ?? null;
            if($dtr){

                ($dtr->hours) ? $absentDays += .5 : '';
                $lateMinutes += $dtr->tardiness;
                $undertimeMinutes += $dtr->undertime;
            }else{
                $absentDays += 1;
            }
        }
     
        $dailyRate = $salary / 22;
        $perMinuteRate = $dailyRate / 480;

        $absenceDeduction = round($dailyRate * $absentDays,2);
        $lateDeduction = $perMinuteRate * $lateMinutes;
        $undertimeDeduction = $perMinuteRate * $undertimeMinutes;
        $totalDeduction = $absenceDeduction + $lateDeduction + $undertimeDeduction;

        return [
            'days' => $absentDays,
            'mins' => $undertimeMinutes + $lateMinutes,
            'total' => $totalDeduction
        ];
    }

    private function generateCode()
    {
        return \DB::transaction(function () {
            $year = date('Y');
            $month = date('m');
            $count = PayrollCycle::whereYear('created_at', $year)
                ->whereNotNull('code')
                ->lockForUpdate()
                ->count();
            $next = $count + 1;
            $code = "R9-{$month}{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
            while (PayrollCycle::where('code', $code)->exists()) {
                $next++;
                $code = "R9-{$month}{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
            return $code;
        });
    }

    private function generateCode2()
    {
        return \DB::transaction(function () {
            $year = date('Y');
            $month = date('m');
            $count = PayrollCutoff::whereYear('created_at', $year)->whereNotNull('code')->lockForUpdate()->count();
            $next = $count + 1;
            $code = "R9CFF-{$month}{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
            while (PayrollCutoff::where('code', $code)->exists()) {
                $next++;
                $code = "R9CFF-{$month}{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
            return $code;
        });
    }

    private function truncateTwoDecimals($value) {
        return floor($value * 100) / 100;
    }
}
