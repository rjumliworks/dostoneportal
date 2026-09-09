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
        $isAllDay = $date->time_of_day === 'Whole Day';

        $startDay = date("M d, Y", strtotime($start));
        $endDay = date("M d, Y", strtotime($end));
        $time = $date->time;

        if ($isAllDay) {
            $displayDate = $startDay == $endDay
                ? $startDay
                : "$startDay to $endDay";
        } else {
            $displayDate = $startDay == $endDay
                ? "$startDay ($time)"
                : "$startDay to $endDay ($time)";
        }

        $firstType = $official->types->first();

        return [
            'id' => $date->id, // unique event id
            'title' => $official->title,
            'start' => Carbon::parse($date->start)->format('Y-m-d'),
            'end' => Carbon::parse($date->end)->format('Y-m-d'),
            'allDay' => $isAllDay,

            'type' => $official->types->pluck('name')->implode(', '),
            'className' => $firstType ? trim($firstType->bg.' '.$firstType->color) : 'bg-primary text-white',

            'full_title' => $official->title,
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
            'type_info' => $official->types,
            'audience' => $official->audience,
            'request' => $official->request,
        ];
    }
}