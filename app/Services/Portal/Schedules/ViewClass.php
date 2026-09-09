<?php

namespace App\Services\Portal\Schedules;

use App\Models\Schedule;
use App\Models\RequestEvent;
use App\Http\Resources\Portal\Schedules\HolidaysResource;
use App\Http\Resources\Portal\Schedules\OfficialResource;

class ViewClass
{
    public function events($request){
        return [
            'holidays' => $this->holidays($request),
            'official' => $this->official($request)
        ];
    }

    public function holidays($request){
        $data = Schedule::with('event')
        ->with('stations.station')
        ->get();
        return HolidaysResource::collection($data);
    }

    public function official()
    {
        $events = RequestEvent::with([
            'request.dates',
            'request.user:id',
            'request.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'travels.request.user:id',
            'travels.request.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'travels.request.tags.user:id',
            'travels.request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'types',
            'mode',
            'audience'
        ])
        ->where('status_id', 26)
        ->get()
        ->flatMap(function ($official) {
            return $official->request->dates->map(function ($date) use ($official) {
                return new OfficialResource([
                    'official' => $official,
                    'date' => $date,
                ]);
            });
        });

        return $events->values();
    }
}
