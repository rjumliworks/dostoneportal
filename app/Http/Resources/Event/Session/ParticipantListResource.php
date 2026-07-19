<?php

namespace App\Http\Resources\Event\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'session_id' => $this->session_id,
            'id' => $this->participant->id,
            'name' => $this->participant->name,
            'email' => $this->participant->email,
            'mobile' => $this->participant->mobile,
            'affiliation' => $this->participant->detail->affiliation,
            'designation' => $this->participant->detail->designation,
            'status' => $this->status,
            'image' => $this->image,
            'attended_at' => $this->attended_at,
            'created_at' => $this->created_at
        ];
    }
}
