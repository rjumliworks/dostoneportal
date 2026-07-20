<?php

namespace App\Http\Resources\Event\Exhibitor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->participant->name,
            'email' => $this->participant->email,
            'mobile' => $this->participant->mobile,
            'affiliation' => $this->participant->detail->affiliation,
            'designation' => $this->participant->detail->designation,
            'avatar' => $this->participant->detail->avatar,
            'voted_at' => $this->voted_at 
        ];
    }
}
