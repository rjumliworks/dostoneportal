<?php

namespace App\Services\HumanResource\Credit;

use App\Models\User;
use App\Models\ListLeave;

class SaveClass
{
    public function store($request){
        $users = User::with('profile')->whereHas('organization', function ($query) {
            $query->where('type_id', 15)->where('status_id',2);
        })->get();

        foreach($users as $user){
            $leaves = ListLeave::where('is_active',1)->where('is_requested',0)->get();
            foreach($leaves as $leave){
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

        $users = User::with('profile')->whereHas('organization', function ($query) {
            $query->where('type_id', 16)->where('status_id',2);
        })->get();

        foreach($users as $user){
            $credit = $user->credits()->create([
                'leave_id' => 14,
                'user_id' => $user->id,
                'balance' => 0,
                'earned' => 0,
                'used' => 0,
                'year' => date('Y')
            ]);
        }
        
        return [
            'data' => '',
            'message' => 'Employee created successfully', 
            'info' => 'You can now manage this employee’s details in the system',
        ];
    }
}
