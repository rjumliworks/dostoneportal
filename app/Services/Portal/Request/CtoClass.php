<?php

namespace App\Services\Portal\Request;

use Carbon\Carbon;
use App\Models\OrgChart;
use App\Models\Request;
use App\Models\RequestSignatory;
use App\Models\RequestReport;

class CtoClass
{
    public function store($request){
        $data = Request::create([
            'code' => $this->generateRequestCode(),
            'type_id' => 165,
            'user_id' => \Auth::user()->id
        ]);
        if($data){
            $divisions = [];
            $signatories = [];

            $signatory = $data->signatories()->create([
                'code' => $this->generateCode($data->type_id),
                'division_id' => 48,
                'status_id' => 24,
                'is_approval_only' => 0
            ]);

            foreach ($request->tags ?? [] as $user) {
                $divisionId = intval($user['division_id']);

                $data->tags()->create([
                    'user_id' => intval($user['value']),
                    'division_id' => $divisionId,
                    'status_id' => 37,
                    'signatory_id' => $signatory->id
                ]);
            }

            $data->detail()->create([
                'purpose' => ($request->purpose) ?  $request->purpose : 'n/a',
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
            }

            $this->report($data->id);
        }

        return [
            'data' => $data,
            'message' => 'Render Overtime Service Request Submitted', 
            'info' => "Your overtime request has been submitted. Keep an eye on your notifications for any approvals or updates."
        ];
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

            $code = 'REQUEST-' . now()->format('Y') . '-CTO-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            return $code;
        });
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

    private function report($id){
        $data = Request::with([
            'tags.user:id',
            'tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'tags.user.organization.division','tags.user.organization.position','tags.user.organization.unit',
            'type',
            'dates',
            'detail',
            'user:id',
            'user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'signatories.division',
            'signatories.approved.user.profile','signatories.approved.signatory.designationable.designation',
            'signatories.recommended.user.profile','signatories.recommended.signatory.designationable.designation'
        ])
        ->where('id',$id)
        ->first();

        $users = $data->tags;
        foreach ($users as $tag) {
            $user = $tag->user;
            $division = $user->organization->division ?? 'n/a';
            $division_id = $user->organization->division->id ?? null;

            $employees[] = [
                'name' => $user->profile->fullname,
                'position' => $user->organization->position->name ?? 'n/a',
                'position_short' => $user->organization->position->short ?? 'n/a',
                'unit' => $user->organization->unit->name ?? 'n/a',
                'unit_short' => $user->organization->unit->short ?? 'n/a',
                'division' => $division->name,
                'division_short' => $division->others,
                'division_id' => $division->id,
            ];

            $divisions[] = $division->name;
        }

        $start = Carbon::parse($data->dates[0]->start);
        $end = Carbon::parse($data->dates[0]->end);
        if($start->format('Y-m-d') === $end->format('Y-m-d')) {
            $formattedDateRange = $start->format('F j, Y');
        }else if($start->format('F Y') === $end->format('F Y')) {
            $formattedDateRange = $start->format('F j') . '–' . $end->format('j, Y');
        }else{
            $formattedDateRange = $start->format('F j, Y') . ' – ' . $end->format('F j, Y');
        }

        if($data->signatories[0]->approved){
            $approved = [
                'name' => $data->signatories[0]->approved->user->profile->fullname,
                'signature' => $data->signatories[0]->approved->user->profile->signature,
                'role' => ($data->signatories[0]->approved->is_designated) ? 'Regional Director' : 'OIC - Regional Director'
            ];
        }else{
            $approved = null;
        }

        if($data->signatories[0]->recommended){
            $recommended = [
                'name' => $data->signatories[0]->recommended->user->profile->fullname,
                'signature' => $data->signatories[0]->recommended->user->profile->signature,
                'role' => ($data->signatories[0]->recommended->is_designated) ? 'Assistant Regional Director' : 'OIC - Assistant Regional Director',
                'division' => $data->signatories[0]->division->others
            ];
        }else{
            $recommended = null;
        }

        $information = [
            'code' => $data->code,
            'purpose' => $data->detail->purpose,
            'date' => $formattedDateRange,
            'employees' => $employees,
            'divisions' => $divisions,
            'approved' => $approved,
            'recommended' => $recommended,
            'signatories' => $this->sign($data->signatories),
            'signatory' => $this->signatory(),
            'created_by' => $data->user->profile->fullname,
            'created_at' => $data->created_at
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

    private function signatory(){
        $a = OrgChart::with('user.profile','oic.profile')->where('designation_id',43)->where('is_active',1)->first(); 
        $approved = [
            'name' => ($a->is_oic) ? $a->oic->profile->fullname : $a->user->profile->fullname,    
            'role' => ($a->is_oic) ? 'OIC - Regional Director' : 'Regional Director'
        ];
        $c = OrgChart::with('user.profile','oic.profile')->where('designation_id',48)->where('is_active',1)->first(); 
        $cto = [
            'name' => ($c->is_oic) ? $c->oic->profile->fullname : $c->user->profile->fullname,    
            'role' => 'Human Resource Management Officer'
        ];
        return [
            'approved' => $approved,
            'cto' => $cto
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
