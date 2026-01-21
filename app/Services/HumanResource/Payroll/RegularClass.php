<?php

namespace App\Services\HumanResource\Payroll;

use Hashids\Hashids;
use App\Models\User;
use App\Models\UserDeduction;
use App\Models\UserOrganization;
use App\Models\Payroll;
use App\Models\PayrollCycle;
use App\Models\PayrollCutoff;
use App\Models\PayrollDeduction;
use App\Http\Resources\HumanResource\Payroll\ListResource;
use App\Http\Resources\HumanResource\Payroll\Regular\CycleResource;
use App\Http\Resources\HumanResource\Payroll\Regular\CutoffResource;

class RegularClass
{
    public function lists($request){
        $data = ListResource::collection(
            PayrollCutoff::with('cycle','status')
            ->with('user:id,username','user.profile:id,user_id,firstname,middlename,lastname,suffix_id')
            ->withSum('payrolls as total', 'netpay')
            ->withSum('payrolls as deduction', 'deduction')
            ->withSum('payrolls as compensation', 'gross')
            ->withCount('payrolls as count')
            ->orderBy('created_at', 'DESC')
            ->whereHas('cycle', function ($query) {
                $query->where('is_regular',1);
            })
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

    public function search($request){
        $keyword = $request->keyword;
        $cutoff_id = $request->cutoff_id;
        
        $data =  User::with([
            'profile:id,user_id,firstname,middlename,lastname,suffix_id',
            'organization.position',
            'organization.division',
            'organization.type',
            'payrolls' => function ($q) use ($cutoff_id) {
                $q->where('cutoff_id', $cutoff_id);
            }
        ])
        ->whereHas('organization', function ($query) {
            $query->where('type_id', 15)->where('status_id',2);
        })
        ->when($request->ids, function ($query) use ($request) {
            $query->whereNotIn('id', $request->ids);
        })
        ->when($keyword, function ($query) use ($keyword){
            $query->whereHas('profile', function ($q) use ($keyword) {
                $q->where('lastname', 'like', '%' . $keyword . '%');
            });
        })
        ->limit(5)->get()->map(function ($item){
            $alreadyInPayroll = $item->payrolls->isNotEmpty();
            return [
                'value' => $item->id,
                'name' => $item->profile->name,
                'position' => optional($item->organization->position)->name,
                'division' => optional($item->organization->division)->name,
                'division_id' => optional($item->organization->division)->id,
                'type' => $item->organization->type->name,
                'avatar' => ($item->profile && $item->profile->avatar && $item->profile->avatar !== 'noavatar.jpg')
                ? asset('storage/' . $item->profile->avatar) 
                : asset('images/avatars/avatar.jpg'), 
                'already_in_payroll' => $alreadyInPayroll
            ];
        });
        return $data;
    }

    public function cycle($request){
        $year = $request->year;
        $month = $request->month;
        $start = date('Y-m-01', strtotime("$year-$month-01"));
        $end = date('Y-m-t', strtotime("$year-$month-01"));

        $cycle = PayrollCycle::where('month',$request->month)->where('year',$request->year)->where('is_regular',1)->first();
        if($cycle){
            $batch = PayrollCutoff::where('type',$request->type)->where('cycle_id',$cycle->id)->count();
            $data = PayrollCutoff::create(
                array_merge($request->all(), [
                    'code' => $this->generateCode2(),
                    'user_id' => \Auth::user()->id,
                    'batch' => $batch + 1,
                    'cycle_id' => $cycle->id,
                    'start' => $start,
                    'end' => $end,
                    'status_id' => 17
                ])
            );
        }else{
            $data = PayrollCycle::create(array_merge($request->all(), [
                'code' => $this->generateCode(),
                'user_id' => \Auth::user()->id,
                'is_regular' => 1
            ]));
            $cutoff = $data->cutoffs()->create(
                array_merge($request->all(), [
                    'code' => $this->generateCode2(),
                    'user_id' => \Auth::user()->id,
                    'batch' => 1,
                    'start' => $start,
                    'end' => $end,
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

    public function payroll($request){
        $data = PayrollCutoff::with('cycle')->where('id', $request->id)->first();
        $existingUserIds = [];

        switch($request->type){
            case 'all':
                $users = User::whereHas('organization', function ($query) {
                    $query->where('type_id', 15)->where('status_id',2);
                })->pluck('id');
            break;
            case 'custom':
                $users = $request->users;
            break;
            case 'except':
                $users = User::whereNotIn('id',$request->users)->whereHas('organization', function ($query) {
                    $query->where('type_id', 15)->where('status_id',2);
                })->pluck('id');
            break;
        }

        foreach($users as $user){
            $exist = Payroll::where('user_id', $user)->where('cutoff_id', $request->id)->first();

            if ($exist) {
                $existingUserIds[] = $user;
                continue; 
            }

            $payroll = $data->payrolls()->create([
                'user_id' => $user,
                'cutoff_id' => $request->id
            ]);

            if($payroll){
                $salary = floatval(str_replace(['₱', ','], '', optional(UserOrganization::with('salary')->where('user_id', $user)->first())->salary?->amount));
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
                $payroll->save();
            }
        }

        return [
            'data' =>['exist' => $existingUserIds],
            'message' => 'Employees added successfully!',
            'info' => "You've successfully created a new cycle."
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
}
