<?php

namespace App\Services\Events\Exhibit;

use App\Models\EventExhibitor;
use App\Models\EventExhibitorContact;

class SaveClass
{
    public function exhibitor($request){
        $data = EventExhibitor::create(array_merge($request->all(),[
            'code' => $this->generateCode()
        ]));
        // if($data){
        //     $data->contact()->create($request->except(['title','institution','description','area','is_active','event_id','option']));
        // }
        return [
            'data' => $data,
            'message' => 'Exhibitor successfully created.',
            'info' => "Great job! Your event is now active and ready for participants."
        ];
    }

    public function contact($request){
        $data = EventExhibitorContact::updateOrCreate(
            ['exhibitor_id' => $request->exhibitor_id],
            $request->only(['name', 'email', 'contact_no'])
        );
        return [
            'data' => $data,
            'message' => 'Contact successfully added.',
            'info' => 'The exhibitor contact details have been saved.'
        ];
    }

    private function generateCode(){
        $count = EventExhibitor::count();
        $code = 'EX-'.date('m').date('Y').'-'.str_pad(($count+1), 4, '0', STR_PAD_LEFT);  //$tsr_count+ remove since it will reset
        return $code;
    }
}
