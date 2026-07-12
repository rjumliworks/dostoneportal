<?php

namespace App\Http\Resources\Portal\Schedules;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $official = $this['official'];
        $date = $this['date'];

        $start = $date->start;
        $end = $date->end;

        $startDay = date("M d, Y", strtotime($start));
        $endDay = date("M d, Y", strtotime($end));
        $startTime = date("g:i a", strtotime($start));
        $endTime = date("g:i a", strtotime($end));

        if ($date->is_allday) {
            $displayDate = $startDay == $endDay
                ? $startDay
                : "$startDay to $endDay";
        } else {
            $displayDate = $startDay == $endDay
                ? "$startDay ($startTime - $endTime)"
                : "$startDay $startTime - $endDay $endTime";
        }

        return [
            'id' => $date->id, // unique event id
            'title' => $official->title,
            'start' => Carbon::parse($date->start)->format('Y-m-d'),
            'end' => Carbon::parse($date->end)->format('Y-m-d'),
            'allDay' => $date->is_allday,

            'type' => $official->type->name,
            'className' => 'bg-primary text-white',

            'full_title' => $official->request->title,
            'datee' => $displayDate,

           'start' => Carbon::parse($date->start)->format('Y-m-d'),
'end' => Carbon::parse($date->end)->format('Y-m-d'),

            'request_event_id' => $official->id,
    'start_date' => date("M d, Y g:i a", strtotime($start)),
    'end_date' => date("M d, Y g:i a", strtotime($end)),

    's_date' => date("Y-m-d H:i", strtotime($start)),
    'e_date' => date("Y-m-d H:i", strtotime($end)),

    'ss_date' => date("M d, Y", strtotime($start)),
    'ee_date' => date("M d, Y", strtotime($end)),

    'day' => date("d", strtotime($start)),
    'day_name' => date("D", strtotime($start)),

            'mode' => $official->mode,
            'type_info' => $official->type,
            'audience' => $official->audience,
            'request' => $official->request,
        ];
    }
}