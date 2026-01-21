<?php

namespace App\Services\HumanResource\Calendar;

use App\Models\Schedule;
use App\Http\Resources\HumanResource\Calendar\ScheduleResource;

class ViewClass
{
    public function events($request){
        $data = Schedule::with('user','event','stations.station')->get();
        return ScheduleResource::collection($data);
    }
}
