<?php

namespace App\Services\Portal\Request;

use Carbon\Carbon;
use App\Models\OrgChart;
use App\Models\Request;
use App\Models\RequestLeave;
use App\Models\RequestSignatory;
use App\Models\RequestReport;
use App\Models\UserCredit;

class LeaveClass
{
    public function store($request){
        $division_id = \Auth::user()->organization->division_id;
        $data = Request::create([
            'code' => $this->generateRequestCode(),
            'type_id' => 158,
            'user_id' => \Auth::user()->id
        ]);
        if($data){
            $signatory = $data->signatories()->create([
                'division_id' => $division_id,
                'code' => $this->generateCode($data->type_id),
                'status_id' => ($division_id == 2) ? 25 : 24,
                'is_approval_only' => ($division_id == 2) ? 1 : 0
            ]);

            $data->tags()->create([
                'user_id' => \Auth::user()->id,
                'division_id' => $division_id,
                'status_id' => 37,
                'signatory_id' => $signatory->id,
            ]);

            $data->detail()->create([
                'purpose' => ($request->details) ?  $request->details : 'n/a',
            ]);

            if($request->date_type != 'Multiple Dates (non-continuous)'){
                $dates = $request->dates;
                $allWholeDay = array_reduce($dates, function ($carry, $item) {
                    return $carry && ($item['timeOfDay'] === 'Whole Day');
                }, true);

                if ($allWholeDay) {
                    $dates = array_column($dates, 'date');
                    $start = min($dates);
                    $end = max($dates);

                    $data->dates()->create([
                        'start' => $start,
                        'end' => $end,
                        'time' => '08:00',
                    ]);
                } else {
                    foreach($dates as $date){
                        $data->dates()->create([
                            'start' => $date['date'],
                            'end' => $date['date'],
                            'time' => '08:00',
                            'time_of_day' => $date['timeOfDay']
                        ]);
                    }
                    
                }
            }else{
                $dates = $request->dates;
                foreach($dates as $date){
                    $data->dates()->create([
                        'start' => $date['date'],
                        'end' => $date['date'],
                        'time' => '08:00',
                        'time_of_day' => $date['timeOfDay']
                    ]);
                }
            }

            $leave = $data->leave()->create([
                'count' => $request->need_credits,
                'pay' => $request->pay,
                'nopay' => $request->nopay,
                'borrowed' => $request->borrowed,
                'detail_id' => $request->detail_id,
                'type_id' => $request->type_id
            ]);
            if($leave){
                if(\Auth::user()->organization->type_id == 15){
                    $types = $request->types;
                    foreach($types as $type){
                        if($type['required_credits']){
                            $credit = UserCredit::where('id',$type['value'])->first();
                            $old_balance = $credit->balance;
                            $credit->balance -= $type['borrow'];
                            $credit->used += $type['borrow'];
                            $credit->save();
                            if($credit){
                                $log = $credit->logs()->create([
                                    'amount' => $type['borrow'],
                                    'old_balance' => $old_balance,
                                    'new_balance' => $credit->balance,
                                    'remarks' => 'Deduction of leave credits for filed leave',
                                    'is_automated' => 1,
                                    'user_id' => 1,
                                    'type_id' => 163
                                ]);
                                if($log){
                                    $leave->credits()->create([
                                        'is_borrowed' => 0,
                                        'log_id' => $log->id,
                                        'credit_id' => $type['value']
                                    ]);
                                }
                            }
                        }else{
                            $credit = new UserCredit;
                            $credit->balance = $type['max_days'] - $request->need_credits;
                            $credit->used = $request->need_credits;
                            $credit->earned = $type['max_days'];
                            $credit->year = date('Y');
                            $credit->user_id = \Auth::user()->id;
                            $credit->leave_id = $type['value'];
                            $credit->save();
                            if($credit){
                                $log = $credit->logs()->create([
                                    'amount' => $request->need_credits,
                                    'old_balance' => $type['max_days'],
                                    'new_balance' => $credit->balance,
                                    'remarks' => 'Deduction of leave credits for filed leave',
                                    'is_automated' => 1,
                                    'user_id' => 1,
                                    'type_id' => 163
                                ]);
                                if($log){
                                    $leave->credits()->create([
                                        'is_borrowed' => 0,
                                        'log_id' => $log->id,
                                        'credit_id' => $credit->id
                                    ]);
                                }
                            }
                        }
                    }

                    $borrowers = $request->borrowers;
                    if(count($borrowers) > 0){
                        foreach($borrowers as $borrower){
                            $credit = UserCredit::where('id',$borrower['value'])->first();
                            $old_balance = $credit->balance;
                            $credit->balance -= $borrower['borrow'];
                            $credit->used += $borrower['borrow'];
                            $credit->save();
                            if($credit){
                                $log = $credit->logs()->create([
                                    'amount' => $borrower['borrow'],
                                    'old_balance' => $old_balance,
                                    'new_balance' => $credit->balance,
                                    'remarks' => 'Leave credits borrowed and deducted for filed leave',
                                    'is_automated' => 1,
                                    'user_id' => 1,
                                    'type_id' => 163
                                ]);
                                if($log){
                                    $leave->credits()->create([
                                        'is_borrowed' => 1,
                                        'log_id' => $log->id,
                                        'credit_id' => $borrower['value']
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            $this->report($data->id,$division_id);
        }

        return [
            'data' => $data,
            'message' => 'Leave Request Submitted', 
            'info' => "Your leave request has been submitted. Keep an eye on your notifications for any approvals or updates."
        ];
    }
    
    private function generateCode($type)
    {
        return \DB::transaction(function () use ($type) {
            $latest = RequestSignatory::lockForUpdate()
                ->whereHas('request', function ($query) use ($type){
                    $query->where('type_id',$type);
                })
                // ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->orderByDesc('id')
                ->first();

            $count = $latest
                ? (int) substr($latest->code, -4) + 1
                : 1;

            $code = now()->format('mY') .'-'.str_pad($count, 4, '0', STR_PAD_LEFT);

            return $code;
        });
    }


    private function generateRequestCode()
    {
        return \DB::transaction(function () {
            $latest = Request::lockForUpdate()
                // ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->orderByDesc('id')
                ->first();

            $count = $latest
                ? (int) substr($latest->code, -4) + 1
                : 1;

            $code = 'REQUEST-' . now()->format('Y') . '-LEAVE-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            return $code;
        });
    }

    private function report($id,$division){
        $data = RequestLeave::with([
            'detail',
            'type',
            'credits.log','credits.credit.leave',
            'request.comments.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.comments.replies.user.profile:user_id,firstname,middlename,lastname,avatar,avatar,suffix_id',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,signature,suffix_id',
            'request.tags.user.organization.position','request.tags.user.organization.salary','request.tags.user.organization.type',
            'request.type',
            'request.dates',
            'request.detail',
            'request.user:id',
            'request.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.user.organization.position','request.user.organization.salary','request.user.organization.division','request.user.organization.unit',
            'request.signatories.division','request.signatories.approved','request.signatories.recommended'
        ])
        ->where('request_id',$id)
        ->first();

        $user = $data->request->tags[0]->user;
  
        $employee = [
            'lastname' => $user->profile->lastname,
            'middlename' => $user->profile->middlename,
            'firstname' => $user->profile->firstname,
            'signature' => $user->profile->signature,
            'position' => $user->organization->position->name ?? 'n/a',
            'salary' => $user->organization->salary->amount ?? 0,
            'position_short' => $user->organization->position->short ?? 'n/a',
            'unit' => $user->organization->unit->name ?? 'n/a',
            'unit_short' => $user->organization->unit->short ?? 'n/a',
            'division' => $user->organization->division->name ?? 'n/a',
            'division_id' => $user->organization->division->id ?? null
        ];

        $divisions[] = $user->organization->division->name;
 

        if (count($data->request->dates) === 1) {
            // Only one date range
            $start = Carbon::parse($data->request->dates[0]->start);
            $end = Carbon::parse($data->request->dates[0]->end);

            if ($start->equalTo($end)) {
                $formattedDateRange = $start->format('F j, Y');
            } elseif ($start->format('F Y') === $end->format('F Y')) {
                $formattedDateRange = $start->format('F j') . '–' . $end->format('j, Y');
            } else {
                $formattedDateRange = $start->format('F j, Y') . ' – ' . $end->format('F j, Y');
            }
        } else {
            // Multiple date ranges
            $ranges = [];

            foreach ($data->request->dates as $date) {
                $start = Carbon::parse($date->start);
                $end = Carbon::parse($date->end);

                if ($start->equalTo($end)) {
                    $ranges[] = $start->format('M j, Y');
                } elseif ($start->format('F Y') === $end->format('M Y')) {
                    $ranges[] = $start->format('M j') . '–' . $end->format('j, Y');
                } else {
                    $ranges[] = $start->format('M j, Y') . ' – ' . $end->format('M j, Y');
                }
            }

            // Join multiple ranges with comma
            $formattedDateRange = implode(', ', $ranges);
        }
    

        $information = [
            'code' => $data->request->code,
            'count' => $data->count,
            'pay' => $data->pay,
            'nopay' => $data->nopay,
            'borrowed' => $data->borrowed,
            'detail' => $data->detail,
            'type' => $data->type,
            'credits' => $data->credits,
            'purpose' => $data->request->detail->purpose,
            'date' => $formattedDateRange,
            'employee' => $employee,
            'divisions' => $divisions,
            'signatory' => $this->signatory($division),
            'signatories' => $this->sign($data->request->signatories),
            'created_by' => $data->request->user->profile->fullname,
            'created_at' => Carbon::parse($data->request->created_at)->format('F d, Y')
        ];

        if(RequestReport::where('request_id',$id)->count() > 0){
            $data = RequestReport::where('request_id',$id)->first();
            $data->information = json_encode($information);
            $data->save();
        }else{
            $data = RequestReport::create([
                'information' => json_encode($information,true),
                'request_id' => $id
            ]);
        }
        return true;
    }

    private function signatory($division){
        $a = OrgChart::with('user.profile','oic.profile')->where('designation_id',43)->where('is_active',1)->first(); 
        $approved = [
            'name' => ($a->is_oic) ? $a->oic->profile->fullname : $a->user->profile->fullname,    
            'role' => ($a->is_oic) ? 'OIC - Regional Director' : 'Regional Director'
        ];
        $c = OrgChart::with('user.profile','oic.profile')->where('designation_id',48)->where('is_active',1)->first(); 
        $hrmo = [
            'name' => ($c->is_oic) ? $c->oic->profile->fullname : $c->user->profile->fullname,    
            'role' => 'Human Resource Management Officer'
        ];
        $d = OrgChart::with('user.profile','oic.profile','assigned')
            ->whereHas('assigned', function ($query) use ($division){
                $query->where('id', $division);
            })
            ->where('designation_id',44)->where('is_active',1)->first(); 
            // dd($d);
        $assigned = $d->assigned->others ?? '';
        $recommended = [
            'name' => ($d->is_oic) ? $d->oic->profile->fullname : $d->user->profile->fullname,
            'role' => ($d->is_oic) ? 'OIC - Assistant Regional Director (' . $assigned . ')' : 'Assistant Regional Director (' . $assigned . ')'
        ];
        return [
            'approved' => $approved,
            'recommended' => $recommended,
            'certified' => $hrmo
        ];
    }

    public function sign($signatories){
        $signatoriesFormatted = [];
        foreach ($signatories as $signatory) {
            $signatoriesFormatted[] = [
                'code' => $signatory->code,
                'division' => $signatory->division->name ?? 'n/a',
                'division_id' => $signatory->division->id ?? null,
                'recommended' => [
                    'name' => $signatory->recommended?->user->profile->fullname,
                    'signature' => $signatory->recommended?->user->profile->signature,
                    'date' =>  $signatory->recommended_date,
                    'role' => null
                ],
                'approved' => [
                    'name' => $signatory->approved?->user->profile->fullname,
                    'signature' => $signatory->approved?->user->profile->signature,
                    'date' => $signatory->approved_date,
                    'role' => null
                ]
            ];
        }

        return $signatoriesFormatted;
    }
}
