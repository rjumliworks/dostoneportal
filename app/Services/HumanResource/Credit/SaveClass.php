<?php

namespace App\Services\HumanResource\Credit;

use App\Models\User;
use App\Models\ListLeave;
use App\Models\UserCredit;

class SaveClass
{
    /**
     * Manually grant leave credits to a single employee (HR-initiated, not
     * the automated yearly accrual). Adds to whatever balance already
     * exists for that employee/leave/year, creating the row if needed, and
     * logs the addition for audit purposes.
     */
    public function addCredit($request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_id' => 'required|exists:list_leaves,id',
            'amount' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string|max:255',
        ]);

        $year = date('Y');

        $credit = UserCredit::firstOrNew([
            'user_id' => $request->user_id,
            'leave_id' => $request->leave_id,
            'year' => $year,
        ]);

        $oldBalance = $credit->exists ? $credit->balance : 0;

        if (!$credit->exists) {
            $credit->balance = 0;
            $credit->earned = 0;
            $credit->used = 0;
            $credit->is_active = 1;
        }

        $credit->balance += $request->amount;
        $credit->earned += $request->amount;
        $credit->save();

        $credit->logs()->create([
            'amount' => $request->amount,
            'old_balance' => $oldBalance,
            'new_balance' => $credit->balance,
            'remarks' => $request->remarks ?: 'Manually added by HR',
            'is_automated' => 0,
            'user_id' => auth()->id(),
            'type_id' => 162,
        ]);

        return [
            'data' => $credit,
            'message' => 'Leave credits added successfully',
            'info' => 'The employee\'s leave balance has been updated.',
        ];
    }

    /**
     * Generate initial leave credit balances (and logs).
     *
     * Pass a $userId to generate for that single, just-created employee only
     * (their organization status is still pending at that point, so the
     * status_id=2 check is skipped). Omit it to bulk-populate every existing
     * active regular/non-regular employee, e.g. for a one-time backfill.
     */
    public function store($userId = null){
        $users = User::with('profile')->whereHas('organization', function ($query) use ($userId) {
            $query->where('type_id', 15);
            if(!$userId){
                $query->where('status_id',2);
            }
        })->when($userId, function ($query) use ($userId) {
            $query->where('id', $userId);
        })->get();

        foreach($users as $user){
            $existingLeaveIds = $user->credits()->where('year',date('Y'))->pluck('leave_id')->all();
            $this->grantFlatCredits($user, $existingLeaveIds);
            $leaves = ListLeave::where('is_active',1)->where('is_requested',0)->get();
            foreach($leaves as $leave){
                if(in_array($leave->id, $existingLeaveIds)){
                    continue;
                }
                if($leave->renewal_period == 'yearly'){
                    if($leave->id == 7){
                        if($user->profile->is_soloparent){
                            $credit = $user->credits()->create([
                                'leave_id' => $leave->id,
                                'user_id' => $user->id,
                                'balance' => $leave->max_days,
                                'earned' => $leave->max_days,
                                'used' => 0,
                                'year' => date('Y')
                            ]);
                        }
                    }else{
                        $credit = $user->credits()->create([
                            'leave_id' => $leave->id,
                            'user_id' => $user->id,
                            'balance' => $leave->max_days,
                            'earned' => $leave->max_days,
                            'used' => 0,
                            'year' => date('Y')
                        ]);
                    }
                    $credit->logs()->create([
                        'amount' => $leave->max_days,
                        'old_balance' => 0,
                        'new_balance' => $leave->max_days,
                        'remarks' => null,
                        'user_id' => 1,
                        'type_id' => 162
                    ]);
                }else if($leave['renewal_period'] == 'monthly'){
                    $monthsPassed = date('n');
                    $earned = $monthsPassed * $leave->accrual_rate;

                    $credit = $user->credits()->create([
                        'leave_id' => $leave->id,
                        'user_id' => $user->id,
                        'balance' => $earned,
                        'earned' => $earned,
                        'used' => 0,
                        'year' => date('Y')
                    ]);

                    $credit->logs()->create([
                        'amount' => $earned,
                        'old_balance' => 0,
                        'new_balance' => $earned,
                        'remarks' => 'Accrual for ' .$monthsPassed.' months',
                        'user_id' => 1,
                        'type_id' => 162
                    ]);
                }

            }
        }

        $users = User::with('profile')->whereHas('organization', function ($query) use ($userId) {
            $query->where('type_id', 16);
            if(!$userId){
                $query->where('status_id',2);
            }
        })->when($userId, function ($query) use ($userId) {
            $query->where('id', $userId);
        })->get();

        foreach($users as $user){
            $existingLeaveIds = $user->credits()->where('year',date('Y'))->pluck('leave_id')->all();
            $this->grantFlatCredits($user, $existingLeaveIds);
        }

        return [
            'data' => '',
            'message' => 'Employee created successfully',
            'info' => 'You can now manage this employee’s details in the system',
        ];
    }

    /**
     * CTO Leave (14) and Wellness Leave (17) aren't accrual-based, so they're
     * granted as flat yearly credits to both regular and non-regular employees.
     */
    private function grantFlatCredits($user, array $existingLeaveIds){
        if(!in_array(14, $existingLeaveIds)){
            $user->credits()->create([
                'leave_id' => 14,
                'user_id' => $user->id,
                'balance' => 0,
                'earned' => 0,
                'used' => 0,
                'year' => date('Y')
            ]);
        }
        if(!in_array(17, $existingLeaveIds)){
            $user->credits()->create([
                'leave_id' => 17,
                'user_id' => $user->id,
                'balance' => 5,
                'earned' => 5,
                'used' => 0,
                'year' => date('Y')
            ]);
        }
    }
}
