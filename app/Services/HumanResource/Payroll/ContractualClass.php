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
        $this->holidays = Schedule::where('event_id', 31)->pluck('start')
        ->map(function ($date) {
            return Carbon::parse($date)->toDateString();
        })->toArray();
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
            $dates = [];
            $period = \Carbon\CarbonPeriod::create($start, $end);

            // Get holidays with both date and title
            $holidays = Schedule::whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('start', '<', $start)
                        ->where('end', '>', $end);
                })
                ->where('event_id', 31)
                ->get(['start', 'title'])
                ->flatMap(function ($holiday) {
                    $dates = [];
                    $startDate = \Carbon\Carbon::parse($holiday->start);
                    $endDate = \Carbon\Carbon::parse($holiday->end ?? $holiday->start);
                    foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $day) {
                        $dates[$day->format('Y-m-d')] = $holiday->title;
                    }
                    return $dates;
                });
                $ignoreDates = array_keys($holidays->toArray());

            // Get official business dates
            $travels = Request::where('type_id', 156)
                ->whereHas('tags', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })
                ->whereHas('dates', function ($q) use ($start, $end) {
                    $q->whereBetween('start', [$start, $end])
                        ->orWhereBetween('end', [$start, $end])
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->where('start', '<', $start)
                                ->where('end', '>', $end);
                        });
                })
                ->with('dates', 'detail')
                ->get();
            // $travels = [];

            foreach ($travels as $travel) {
                foreach ($travel->dates as $travelDate) {
                    $startDate = \Carbon\Carbon::parse($travelDate->start);
                    $endDate = \Carbon\Carbon::parse($travelDate->end ?? $travelDate->start);
                    foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $day) {
                        $officialTravel[$day->format('Y-m-d')] = $travel->location->address.', '.$travel->location->municipality->name ?? 'Official Travel';
                    }
                }
            }

            $obs = Request::where('type_id', 192)
                ->whereHas('tags', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })
                ->whereHas('dates', function ($q) use ($start, $end) {
                    $q->whereBetween('start', [$start, $end])
                        ->orWhereBetween('end', [$start, $end])
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->where('start', '<', $start)
                                ->where('end', '>', $end);
                        });
                })
                ->with('dates', 'detail','event')
                ->get();
            // $travels = [];

            foreach ($obs as $ob) {
                foreach ($ob->dates as $obDate) {
                    $startDate = \Carbon\Carbon::parse($obDate->start);
                    $endDate = \Carbon\Carbon::parse($obDate->end ?? $obDate->start);
                    foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $day) {
                        $officialBusiness[$day->format('Y-m-d')] = $ob->event->title ?? 'Official Business';
                    }
                }
            }



            // Generate daily data
            $dates = [];
            foreach ($period as $date) {
                $dateStr = $date->toDateString();
                // if ($date->isSaturday() || $date->isSunday()) {
                //     continue;
                // }

                $status = null;
                $title = null;

                if ($date->isSaturday() || $date->isSunday()) {
                    $status = 'Non-working Day';
                    $title = 'Non-working Day';
                }elseif(isset($holidays[$dateStr])) {
                    $status = 'Holiday';
                    $title = $holidays[$dateStr];
                }elseif(isset($officialTravel[$dateStr])) { 
                    $status = 'Official Travel';
                    $title = $officialTravel[$dateStr];
                }elseif(isset($officialBusiness[$dateStr])) { 
                    $status = 'Official Business';
                    $title = $officialBusiness[$dateStr];
                }

                // $dtr = $item->dtrs->firstWhere('date', $dateStr);
                if (
                    in_array($dateStr, $ignoreDates) ||
                    in_array(Carbon::parse($dateStr)->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])
                ) {
                    continue; // skip this date
                }

                $dtr = $item->dtrs->firstWhere('date', $dateStr);

                $dates[] = [
                    'date' => $dateStr,
                    'am_in' => ($dtr && $dtr->am_in_at) ? new TimeResource(json_decode($dtr->am_in_at)) : null,
                    'am_out' => ($dtr && $dtr->am_out_at) ? new TimeResource(json_decode($dtr->am_out_at)) : null,
                    'pm_in'  => ($dtr && $dtr->pm_in_at)  ? new TimeResource(json_decode($dtr->pm_in_at))  : null,
                    'pm_out' => ($dtr && $dtr->pm_out_at) ? new TimeResource(json_decode($dtr->pm_out_at)) : null,
                    'is_completed' => ($dtr ? $dtr->is_completed : null),
                    'status' => $status ?? ($dtr ? 'Present' : 'Absent'),
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
                'avatar' => ($item->profile && $item->profile->avatar && $item->profile->avatar !== 'noavatar.jpg')
                ? asset('storage/' . $item->profile->avatar) 
                : asset('images/avatars/avatar.jpg'), 
                'already_in_payroll' => $alreadyInPayroll,
                'dtrs' => $alreadyInPayroll ? [] : $dates
            ];
        });
        return $data;
    }

    private function tardiness($data,$user,$salary){
        $start = Carbon::parse($data->start);
        $end = Carbon::parse($data->end);
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

        $datesList = $datesList->unique()->sort()->values();
        $combinedDates = $datesList->merge($this->holidays)->unique()->sort()->values();

        $period = CarbonPeriod::create($start, $end);
        $filteredPeriod = collect($period)->reject(function ($date) use ($combinedDates){
            return in_array($date->toDateString(), $combinedDates->toArray());
        });
        $lateMinutes = 0;
        $undertimeMinutes = 0;
        $absentDays = 0;

        $dtrs = Dtr::where('user_id',$user)
        ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
        ->whereNotIn('date', $this->holidays)
        ->get()
        ->keyBy(fn ($dtr) => Carbon::parse($dtr->date)->toDateString());
        $test = [];

        foreach ($filteredPeriod as $day) {
            if ($day->isWeekend()) {
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
