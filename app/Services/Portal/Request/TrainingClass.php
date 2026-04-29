<?php

namespace App\Services\Portal\Request;

use App\Models\OrgChart;
use App\Models\Request;
use App\Models\RequestReport;
use App\Models\RequestSignatory;

class TrainingClass
{
    public function store($request){
        $division_id = \Auth::user()->organization->division_id;
        $data = Request::create([
            'code' => $this->generateRequestCode(),
            'type_id' => 196,
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
            $this->report($data->id,$division_id);
        }

        return [
            'data' => $data,
            'message' => 'Leave Request Submitted', 
            'info' => "Your leave request has been submitted. Keep an eye on your notifications for any approvals or updates."
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

            $code = 'REQUEST-' . now()->format('Y') . '-TRAINING-' . str_pad($count, 4, '0', STR_PAD_LEFT);

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
            'employees' => $employees,
            'divisions' => $divisions,
            'approved' => $approved,
            'recommended' => $recommended,
            'signatories' => $this->sign($data->signatories),
            'signatory' => $this->signatory($data->signatories),
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

    private function signatory($divisions){
        $a = OrgChart::with('user.profile','oic.profile')->where('designation_id',43)->where('is_active',1)->first(); 
        $approved = [
            'name' => ($a->is_oic) ? $a->oic->profile->fullname : $a->user->profile->fullname,    
            'role' => ($a->is_oic) ? 'OIC - Regional Director' : 'Regional Director'
        ];
        foreach($divisions as $division){
            $d = OrgChart::with('user.profile','oic.profile','assigned')
            ->whereHas('assigned', function ($query) use ($division){
                $query->where('id', $division->division_id);
            })
            ->where('designation_id',44)->where('is_active',1)->first(); 
            if ($d) {
                $assigned = $d->assigned->others ?? '';
                $recommended[] = [
                    'name' => ($d->is_oic) ? $d->oic->profile->fullname : $d->user->profile->fullname,
                    'role' => ($d->is_oic) ? 'OIC - Assistant Regional Director (' . $assigned . ')' : 'Assistant Regional Director (' . $assigned . ')'
                ];
            } else {
                $recommended[] = [
                    'name' => '',
                    'role' => ''
                ];
            }
        }
        return [
            'approved' => $approved,
            'recommended' => $recommended
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
