<?php

namespace App\Http\Resources\Api\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'link' => $this->link,
            'email' => $this->email,
            'contact_no' => $this->contact_no,
            'avatar' => asset('storage/signatures/sLkhA2m4vevnQieWPYaaqAn3hlBTU1k1aqikuvBj.png'),
            'location' => $this->location,
            'rates' => $this->rates
        ];
    }
}
