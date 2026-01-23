<?php

namespace App\Services\Portal\Request;

use Carbon\Carbon;
use App\Models\OrgChart;
use App\Models\Request;
use App\Models\RequestTravel;
use App\Models\RequestReport;
use App\Models\RequestSignatory;

class TravelClass
{
    public function store($request){
        $data = Request::create([
            'code' => $this->generateCode(),
            'type_id' => 156,
            'user_id' => \Auth::user()->id
        ]);
        if($data){
            $divisions = [];
            $signatories = [];

            foreach ($request->tags ?? [] as $user) {
                $divisionId = intval($user['division_id']);

                if(!in_array($divisionId, $divisions)) {
                    $divisions[] = $divisionId;
                }

                if(!empty($user['signatory'])) {
                    $signatory = $data->signatories()->create([
                        'division_id' => $divisionId,
                        'code' => $this->generateTravelSoloCode($divisionId,$data->type_id),
                        'status_id' => 25,
                        'is_approval_only' => 1
                    ]);
                }else{
                    $isApprovalOnly = ($divisionId == 2) ? 1 : 0;
                    $signatory = $data->signatories()->where('division_id', $divisionId)->first();
                    
                    if($signatory) {
                        if(($divisionId != 2)){
                            if($signatory->is_approval_only == 1){
                                $signatory->update([
                                    'is_approval_only' => 0, 
                                ]);
                            }
                        }
                    }elseif(!$signatory) {
                        $signatory = $data->signatories()->create([
                            'code' => $this->generateTravelSoloCode($divisionId,$data->type_id),
                            'division_id' => $divisionId,
                            'status_id' => ($isApprovalOnly) ? 25 : 24,
                            'is_approval_only' => $isApprovalOnly
                        ]);
                    }
                    $signatories[$divisionId] = $signatory->id;
                }
                $data->tags()->create([
                    'user_id' => intval($user['value']),
                    'division_id' => $divisionId,
                    'status_id' => 37,
                    'signatory_id' => $signatory->id, // Directly assign the signatory_id
                ]);
            }
            
            if(strpos($request->date, ' to ') !== false) {
                [$start, $end] = explode(' to ', $request->date);
            } else {
                $start = $end = $request->date;
            }

            $start = \Carbon\Carbon::parse($start)->toDateString();
            $end = \Carbon\Carbon::parse($end)->toDateString();

            $data->dates()->create([
                'start' => $start,
                'end' => $end,
                'time' => $request->time,
            ]);

            $data->detail()->create($request->only([
                'purpose', 'remarks'
            ]));
            $data->location()->create($request->only([
                'address','longitude','latitude','barangay_code','municipality_code','province_code','region_code'
            ]));

            $travelData = [
                'code' => $this->generateTravelCode(),
                'mode_id' => $request->mode_id,
                'transpo_id' => $request->transpo_id,
                'expense_id' => $request->expense_id,
                'expenses' => array_map('intval', $request->expenses)
            ];
            $travel = $data->travel()->create($travelData);
          
            if($request->mode_id == 150){
                $data->reservation()->create([
                    'vehicle_id' => $request->vehicle['value'],
                    'driver_id' => $request->vehicle['driver_id']
                ]);
                $signatory = $data->signatories()->where('division_id', $divisionId)->first();
                if(!$signatory) {
                    $signatory = $data->signatories()->create([
                        'code' => $this->generateTravelSoloCode($divisionId,$data->type_id),
                        'division_id' => $divisionId,
                        'status_id' => 24,
                        'is_approval_only' => 0
                    ]);
                }
                $data->tags()->create([
                    'user_id' => $request->vehicle['driver_id'],
                    'division_id' => 3,
                    'signatory_id' => $signatory->id,
                    'is_driver' => 1
                ]);
            }
            $this->report($data->id);
        }

        return [
            'data' => $data,
            'message' => 'Travel Request Submitted', 
            'info' => "Your travel schedule has been submitted. Keep an eye on your notifications for any approvals or updates."
        ];
    }
    
    private function generateCode()
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

            $code = 'REQUEST-' . now()->format('Y') . '-TRAVEL-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            return $code;
        });
    }

    private function generateTravelCode()
    {
        return \DB::transaction(function () {
            $latest = RequestTravel::lockForUpdate()
                // ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->orderByDesc('id')
                ->first();

            $count = $latest
                ? (int) substr($latest->code, -4) + 1
                : 1;

            $code = 'TRVL-'.now()->format('mY') .'-'.str_pad($count, 4, '0', STR_PAD_LEFT);

            return $code;
        });
    }

    private function generateTravelSoloCode($divisionId,$type)
    {
        return \DB::transaction(function () use ($divisionId,$type) {
            $latest = RequestSignatory::lockForUpdate()
                ->whereHas('request', function ($query) use ($type){
                    $query->where('type_id',$type);
                })
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->orderByDesc('id')
                ->first();

            $count = $latest
                ? (int) substr($latest->code, -4) + 1
                : 1;

            $code = now()->format('Y') .'-'.str_pad($count, 4, '0', STR_PAD_LEFT);

            return $code;
        });
    }

    public function report($id){
        $data = Request::with([
            'travel.mode',
            'travel.expense',
            'reservation.vehicle',
            'type',
            'dates',
            'detail',
            'tags.user:id','tags.user.profile:user_id,firstname,middlename,lastname,avatar','tags.user.organization.division','tags.user.organization.position','tags.user.organization.unit',
            'signatories.division','signatories.approved.user.profile:user_id,firstname,middlename,lastname,suffix_id,signature','signatories.recommended.user.profile:user_id,firstname,middlename,lastname,suffix_id,signature',
            'location.region:code,name,region','location.province:code,name','location.municipality:code,name','location.barangay:code,name'
        ])
        ->where('id',$id)
        ->first();

        $users = $data->tags;
        foreach ($users as $tag) {
            $user = $tag->user;

            $profile = $user->profile;
            $middleInitial = $profile->middlename ? strtoupper(substr($profile->middlename, 0, 1)) . '.' : '';
            $fullName = "{$profile->firstname} {$middleInitial} {$profile->lastname}";

            $division = $user->organization->division->name ?? 'n/a';
            $division_id = $user->organization->division->id ?? null;

            $employees[] = [
                'name' => $fullName,
                'position' => $user->organization->position->name ?? 'n/a',
                'position_short' => $user->organization->position->short ?? 'n/a',
                'unit' => $user->organization->unit->name ?? 'n/a',
                'unit_short' => $user->organization->unit->short ?? 'n/a',
                'division' => $division,
                'division_id' => $division_id,
                'is_driver' => $tag->is_driver
            ];
        }

        $start = Carbon::parse($data->dates[0]->start);
        $end = Carbon::parse($data->dates[0]->end);

        if ($start->format('F Y') === $end->format('F Y')) {
            $formattedDateRange = $start->format('F j') . '–' . $end->format('j, Y');
        } else {
            $formattedDateRange = $start->format('F j, Y') . ' – ' . $end->format('F j, Y');
        }

        $information = [
            'code' => $data->code,
            'travel_code' => $data->travel->code,
            'purpose' => $data->detail->purpose,
            'remarks' => $data->detail->remarks,
            'mode' => $data->travel->mode->name,
            'vehicle' => ($data->travel->mode_id == 150) ? $data->reservation->vehicle->name.' ('.$data->reservation->vehicle->plate.')' : null, 
            'expense' => $data->travel->expense->name,
            'transpo' => $data->travel->transpo?->name ?? '-',
            'time' => $data->dates[0]->time,
            'date' => $formattedDateRange,
            'duration' => $dayDuration = ($start->diffInDays($end) + 1) . ' ' . (($start->diffInDays($end) + 1) === 1 ? 'day' : 'days'),
            'expenses' => $data->travel->expense_items, 
            'destination' => $data->location->barangay->name.', '.$data->location->municipality->name,
            'venue' => $data->location->address,
            'employees' => $employees,
            'signatories' => $this->sign($data->signatories),
            'signatory' => $this->signatory($data->signatories),
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
