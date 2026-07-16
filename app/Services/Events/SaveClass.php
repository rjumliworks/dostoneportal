<?php

namespace App\Services\Events;

use App\Models\Event;

class SaveClass
{
    public function event($request){
        $event = Event::create(array_merge($request->all(),[
            'code' => $this->generateCode(),
            'user_id' => \Auth::user()->id
        ]));
        if($event){
            $event->detail()->create($request->except(['name','year','start','end','is_active','option']));
        }
        return [
            'data' => $event,
            'message' => 'Event successfully created.', 
            'info' => "Great job! Your event is now active and ready for participants."
        ];
    }

    private function generateCode(){
        $count = Event::count();
        $code = 'EVENT-'.date('m').date('Y').'-DOSTIX-'.str_pad(($count+1), 4, '0', STR_PAD_LEFT);  //$tsr_count+ remove since it will reset
        return $code;
    }
}
