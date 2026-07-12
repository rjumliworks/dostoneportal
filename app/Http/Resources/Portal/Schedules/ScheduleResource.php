<?php

namespace App\Http\Resources\Portal\Schedules;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $start =  date("M d, Y",strtotime($this->start));
        $end =  date("M d, Y",strtotime($this->end));
        $s_time = date("g:i a",strtotime($this->start));
        $e_time = date("g:i a",strtotime($this->end));
        if($this->is_allday){
            if($start == $end){
                $date = $start;
            }else{
                $date = $start.' to '.$end;
            }
        }else{
            if($start == $end){
                $date = $start.'  ('.$s_time.' - '.$e_time.')';
            }else{
                $date = $start.' '.$s_time.' - '.$end.' '.$e_time;
            }
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'type' => $this->event->name,
            'className' => $this->event->bg.' '.$this->event->color,
            'full_title' => $this->title,
            'full_name' => $this->user->profile->firstname.' '.$this->user->profile->lastname,
            'start_date' => date("M d, Y g:i a",strtotime($this->start)),
            'end_date' => date("M d, Y g:i a",strtotime($this->start)),
            's_date' => date("Y-m-d H:i",strtotime($this->start)),
            'e_date' => date("Y-m-d H:i",strtotime($this->end)),
            'ss_date' => date("M d, Y",strtotime($this->start)),
            'ee_date' => date("M d, Y",strtotime($this->end)),
            'day' => date("d", strtotime($this->start)),
            'day_name' => date("D", strtotime($this->start)),
            'datee' => $date,
            'stations' => $this->stations,
            'is_allday' => $this->is_allday,
            'event' => $this->event,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at
        ];
    }
}
