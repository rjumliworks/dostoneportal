<?php

namespace App\Http\Resources\Api\Events\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
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
            'participant_id' => $this->participant_id,
            'name' => $this->participant->name,
            'email' => $this->participant->email,
            'mobile' => $this->participant->mobile,
            'affiliation' => $this->participant->detail->affiliation,
            'designation' => $this->participant->detail->designation,
            'image' => $this->image,
            'status' => $this->status,
            'attended_at' => $this->attended_at,
            'created_at' => $this->created_at
        ];
    }
}
