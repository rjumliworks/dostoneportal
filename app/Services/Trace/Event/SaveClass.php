<?php

namespace App\Services\Trace\Event;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Request;
use App\Models\RequestEvent;
use App\Models\RequestReport;
use App\Http\Resources\Trace\Event\IndexResource;

class SaveClass
{
    public function participant($request){
        $data = Request::findOrFail($request->id);
        $data->tags()->create([
            'user_id' => $request->user_id,
            'division_id' => $request->division_id,
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
            $this->saveDates($data, $request);

            $data->detail()->create($request->only([
                'purpose', 'remarks'
            ]));
            $data->location()->create($request->only([
                'address','longitude','latitude','barangay_code','municipality_code','province_code','region_code'
            ]));

            $eventData = [
                'title' => $request->title,
                'is_host' => $request->is_host,
                'is_managed' => $request->is_managed,
                'mode_id' => $request->mode_id,
                'audience_id' => $request->audience_id,
                'status_id' => 26,
                'user_id' => \Auth::user()->id
            ];
            $event = $data->event()->create($eventData);
            $event->types()->sync($request->types);

            if ($request->is_managed) {
                $this->createManagedEvent($request);
            }

            $this->report($data->id);
        }

        return [
            'data' => new IndexResource($this->loadedEvent($event->id)),
            'message' => 'Event created Successfully',
            'info' => "Your travel schedule has been submitted. Keep an eye on your notifications for any approvals or updates."
        ];
    }

    public function update($request){
        $event = RequestEvent::with('request')->findOrFail($request->id);
        $data = $event->request;

        $event->update([
            'title' => $request->title,
            'is_host' => $request->is_host,
            'is_managed' => $request->is_managed,
            'mode_id' => $request->mode_id,
            'audience_id' => $request->audience_id,
        ]);
        $event->types()->sync($request->types);

        $data->dates()->delete();
        $this->saveDates($data, $request);

        $data->detail()->update($request->only([
            'purpose', 'remarks'
        ]));
        $data->location()->update($request->only([
            'address','longitude','latitude','barangay_code','municipality_code','province_code','region_code'
        ]));

        $this->report($data->id);

        return [
            'data' => new IndexResource($this->loadedEvent($event->id)),
            'message' => 'Event updated Successfully',
            'info' => "The event details have been updated."
        ];
    }

    private function loadedEvent($id){
        return RequestEvent::with([
            'mode','types','audience',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id','request.tags.status',
            'request.tags.user.organization.division','request.tags.user.organization.unit','request.tags.user.organization.position',
            'request.dates',
            'request.location',
            'request.detail',
        ])->find($id);
    }

    private function saveDates($data, $request){
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
    }

    private function createManagedEvent($request){
        $dates = array_column($request->dates, 'date');
        $start = min($dates);
        $end = max($dates);

        $event = Event::create([
            'code' => $this->generateEventCode(),
            'name' => $request->title,
            'year' => date('Y', strtotime($start)),
            'start' => $start,
            'end' => $end,
            'is_active' => true,
            'user_id' => \Auth::user()->id,
        ]);

        $event->detail()->create([
            'description' => $request->purpose,
            'venue' => $request->address,
            'address' => $request->address,
            'region_code' => $request->region_code,
            'province_code' => $request->province_code,
            'municipality_code' => $request->municipality_code,
            'barangay_code' => $request->barangay_code,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);

        return $event;
    }

    private function generateEventCode(){
        $count = Event::count();
        return 'EVENT-'.date('m').date('Y').'-DOSTIX-'.str_pad(($count+1), 4, '0', STR_PAD_LEFT);
    }

    public function report($id){
        $data = Request::with([
            'event.mode','event.types','event.audience',
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
            'type' => $data->event->types->pluck('name')->implode(', '),
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
