<?php

namespace App\Http\Resources\Api\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'attended_at' => $this->attended_at,
            'avatar' => asset('storage/'.$this->image),
            'participant_id' => $this->participant->code,
            'participant' => $this->participant,
            'session' => $this->session
        ]; 
    }
}
