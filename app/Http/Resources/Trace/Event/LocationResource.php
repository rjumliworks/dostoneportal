<?php

namespace App\Http\Resources\Trace\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $address = ($this->address != '' || $this->address != NULL) ? $this->address.', ' : '';
        if($this->municipality->name == 'Zamboanga City' || $this->municipality->name == 'Isabela City'){
            $a = '';
        }else if($this->province->name == 'Sulu'){
            $a = ', '.$this->province->name;
        }else{
            $a = ', '.$this->province->name.', '.$this->region->region;
        }
        return [
            'name' => $address.$this->barangay->name.', '.$this->municipality->name,
            'address' => $this->address,
            'region' => $this->region,
            'province' => $this->province,
            'municipality' => $this->municipality,
            'barangay' => $this->barangay,
        ];
    }
}
