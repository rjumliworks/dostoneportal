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
        ->when($keyword, function ($query) use ($keyword){
            $query->whereHas('profile', function ($q) use ($keyword) {
                $q->where('lastname', 'like', '%' . $keyword . '%');
            });
        })
        ->limit(5)->get()->map(function ($item) use ($start, $end){
            $alreadyInPayroll = $item->payrolls->isNotEmpty();
            $user_id = $item->id;
            $station_id = $item->organization->station_id;
            $dates = [];
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
            $ignoreDates = array_keys($holidays->toArray());

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
                        $officialTravel[$day->format('Y-m-d')] =
                            ($travel->location->address . ', ' . $travel->location->municipality->name)
                            ?? 'Official Travel';
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
                        $officialBusiness[$day->format('Y-m-d')] = $ob->event->title ?? 'Official Business';
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
                        $officialLeave[$day->format('Y-m-d')] = $ob->leave->type->name ?? 'Leave';
                    }
                }
            }


            $uniqueDays = $item->organization->shift->times->pluck('days') 
            ->flatMap(function ($days) {        
                return explode(',', $days);
            })->unique()->values();

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

            return [
                'value' => $item->id,
                'name' => $item->profile->name,
                'position' => optional($item->organization->position)->name,
                'division' => optional($item->organization->division)->name,
                'division_id' => optional($item->organization->division)->id,
                'type' => $item->organization->type->name,
                'avatar' => $item->profile->avatar, 
                'already_in_payroll' => $alreadyInPayroll,
                'dtrs' => $alreadyInPayroll ? [] : $dates
            ];
        });
        return $data;
    }

    public function payroll($request){
      
        $data = PayrollCutoff::with('cycle')->where('id', $request->id)->first();

        $user = $request->user_id;
        $exist = Payroll::where('user_id', $user)->where('cutoff_id', $request->id)->first();

        if(!$exist){
            $payroll = $data->payrolls()->create([
                'user_id' => $user,
                'cutoff_id' => $request->id
            ]);
            
            if ($payroll) {
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
            'data' =>[],
            'message' => 'Employees added successfully!',
            'info' => "You've successfully created a new cycle."
        ];
    }

    private function tardiness($data,$user,$salary){
        $start = Carbon::parse($data->start);
        $end = Carbon::parse($data->end);
        $employee = User::with('organization.shift.times')->findOrFail($user);

        $workingDays = $employee->organization->shift->times
        ->pluck('days')
        ->flatMap(function ($days) {
            return explode(',', $days);
        })
        ->map(fn ($day) => (int) trim($day))
        ->unique()
        ->values();
        $datesList = collect();
        $travels = Request::where('type_id',156)
        ->whereHas('tags', function ($query) use ($user) {
            $query->where('user_id', $user);
        })
        ->whereHas('dates', function ($q) use ($start, $end) {
            $q->whereBetween('start', [$start, $end]) // starts this month
            ->orWhereBetween('end', [$start, $end]) // ends this month
            ->orWhere(function ($q2) use ($start, $end) { // spans whole month
                $q2->where('start', '<', $start)
                    ->where('end', '>', $end);
            });
        })
        ->with('dates')
        ->get();
        $obs = Request::where('type_id',192)
        ->whereHas('tags', function ($query) use ($user) {
            $query->where('user_id', $user);
        })
        ->whereHas('dates', function ($q) use ($start, $end) {
            $q->whereBetween('start', [$start, $end]) // starts this month
            ->orWhereBetween('end', [$start, $end]) // ends this month
            ->orWhere(function ($q2) use ($start, $end) { // spans whole month
                $q2->where('start', '<', $start)
                    ->where('end', '>', $end);
            });
        })
        ->with('dates')
        ->get();
        // $travels = [];
        // $obs = [];
       

        $approvedLeaves = Request::where('type_id', 158)
        ->whereHas('tags', fn($q) => $q->where('user_id', $user))
        ->withWhereHas('dates', function ($q) use ($start, $end) {
            $q->whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('start', '<', $start)
                        ->where('end', '>', $end);
                });
        })
        ->get();

        dd($approvedLeaves);

        foreach ($travels as $travel) {
            foreach ($travel->dates as $range) {
                $current = Carbon::parse($range->start);
                $endDate = Carbon::parse($range->end);

                while ($current->lte($endDate)) {
                    $datesList->push($current->toDateString());
                    $current->addDay();
                }
            }
        }

           foreach ($obs as $ob) {
            foreach ($ob->dates as $range) {
                $current = Carbon::parse($range->start);
                $endDate = Carbon::parse($range->end);

                while ($current->lte($endDate)) {
                    $datesList->push($current->toDateString());
                    $current->addDay();
                }
            }
        }
$leaves = collect();
        foreach ($approvedLeaves as $leave) {
            $current = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);

            while ($current->lte($endDate)) {
                $leaves->push($current->toDateString());
                $current->addDay();
            }
        }

        dd($leaves);

        $datesList = $datesList->unique()->sort()->values();
        $ignoredDates = $datesList
            ->merge($this->holidays)
            ->merge($leaves)
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
        ->whereNotIn('date', $ignoredDates)
        ->get()
        ->keyBy(fn ($dtr) => Carbon::parse($dtr->date)->toDateString());
        $test = [];

        foreach ($filteredPeriod as $day) {
            $dayNumber = $day->dayOfWeekIso;

            if (! $workingDays->contains($dayNumber)) {
                continue;
            }

            $dayString = $day->toDateString();
            $dtr = $dtrs[$dayString] ?? null;
            if($dtr){
                $hasAmLogs = !empty($dtr->am_in_at) && !empty($dtr->am_out_at);
                $hasPmLogs = !empty($dtr->pm_in_at) && !empty($dtr->pm_out_at);
            
                if (!$hasAmLogs) {
                    $test[] = $dtr;
                    $absentDays += 0.5;
                }

                if (!$hasPmLogs) {
                     $test[] = $dtr;
                    $absentDays += 0.5;
                }

                if ($hasAmLogs && $hasPmLogs) {
                    $amin = json_decode($dtr->am_in_at);
                    $amout = json_decode($dtr->am_out_at);
                    $pmin = json_decode($dtr->pm_in_at);
                    $pmout = json_decode($dtr->pm_out_at);

                    $lateMinutes += $amin->minutes + $pmin->minutes;
                    $undertimeMinutes += $amout->minutes + $pmout->minutes;
                }
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
