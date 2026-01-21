<?php

namespace App\Services\Trace\Event;

use Carbon\Carbon;
use App\Models\Request;
use App\Models\RequestReport;

class SaveClass
{
    public function participant($request){
        $data = Request::findOrFail($request->id);
        $data->tags()->create([
            'user_id' => $request->user_id,
            'division_id' => $request->division_id,
            'signatory_id' => 1,
            'status_id' => 36,
        ]);
        return [
            'data' => $data,
            'message' => 'Participant added successfully.',
            'info' => 'The participant has been added to the event. You may review or update participant details anytime.'
        ];
    }

    public function store($request){
        $data = Request::create([
            'code' => $this->generateCode(),
            'type_id' => 192,
            'user_id' => \Auth::user()->id
        ]);
        if($data){ 
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

            $data->detail()->create($request->only([
                'purpose', 'remarks'
            ]));
            $data->location()->create($request->only([
                'address','longitude','latitude','barangay_code','municipality_code','province_code','region_code'
            ]));

            $eventData = [
                'title' => $request->title,
                'is_host' => $request->is_host,
                'mode_id' => $request->mode_id,
                'type_id' => $request->type_id,
                'audience_id' => $request->audience_id,
            ];
            $data->event()->create($eventData);
            $this->report($data->id);
        }

        return [
            'data' => $data,
            'message' => 'Event created Successfully', 
            'info' => "Your travel schedule has been submitted. Keep an eye on your notifications for any approvals or updates."
        ];
    }

    public function report($id){
        $data = Request::with([
            'event.mode','event.type','event.audience',
            'dates',
            'detail',
            'tags.user:id','tags.user.profile:user_id,firstname,middlename,lastname,avatar','tags.user.organization.division','tags.user.organization.position','tags.user.organization.unit',
            'location.region:code,name,region','location.province:code,name','location.municipality:code,name','location.barangay:code,name'
        ])
        ->where('id',$id)
        ->first();
        $employees = [];
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
            'purpose' => $data->detail->purpose,
            'remarks' => $data->detail->remarks,
            'title' => $data->event->title, 
            'type' => $data->event->type->name, 
            'mode' => $data->event->mode->name, 
            'audience' => $data->event->audience->name, 
            'time' => $data->dates[0]->time,
            'date' => $formattedDateRange,
            'destination' => $data->location->barangay->name.', '.$data->location->municipality->name,
            'venue' => $data->location->address,
            'employees' => $employees,
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

    
    private function generateCode()
    {
        return \DB::transaction(function () {
            $latest = Request::lockForUpdate()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->orderByDesc('id')
                ->first();

            $count = $latest
                ? (int) substr($latest->code, -4) + 1
                : 1;

            $code = 'REQUEST-' . now()->format('mY') . '-EVENT-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            return $code;
        });
    }
}
