<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attended_at' => $this->attended_at,
            'avatar' => asset('storage/'.$this->image),
            'participant_id' => $this->participant->code,
            'participant' => $this->participant
        ];
    }
}
