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
            'type',
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
