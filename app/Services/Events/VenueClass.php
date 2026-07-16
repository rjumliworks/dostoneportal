<?php

namespace App\Services\Events;

use App\Models\EventVenue;

class VenueClass
{
    public function save($request){
        $event = EventVenue::create(array_merge($request->all(),[
            'code' => $this->generateCode()
        ]));
        return [
            'data' => $event,
            'message' => 'Venue successfully added.', 
            'info' => "Great job! You can now add sessions associated with this venue."
        ];
    }

    public function update($request){
        $data = EventVenue::findOrFail($request->id);
        $data->updateIfDirty($request->only('name','establishment','address'));
        return [
            'data' => $data,
            'message' => 'Venue successfully aupdated.', 
            'info' => "You've successfully update the venue."
        ];
    }

    private function generateCode(){
        $count = EventVenue::count();
        $code = 'VN-'.date('m').date('Y').'-DOSTIX-'.str_pad(($count+1), 4, '0', STR_PAD_LEFT);  //$tsr_count+ remove since it will reset
        return $code;
    }
}
