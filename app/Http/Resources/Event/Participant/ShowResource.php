<?php

namespace App\Http\Resources\Event\Participant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'avatar' => $this->detail?->avatar,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'birthdate' => $this->detail?->birthdate,
            'age' => $this->detail?->age,
            'sex' => $this->detail?->sex,
            'designation' => $this->detail?->designation,
            'type' => $this->detail?->type,
            'affiliation' => $this->detail?->affiliation,
            'others' => $this->detail?->others,
            'is_completed' => $this->detail?->is_completed,
            'created_at' => $this->created_at,
            'sessions' => $this->sessions->map(function ($sessionParticipant) {
                return [
                    'id' => $sessionParticipant->id,
                    'title' => $sessionParticipant->session?->title,
                    'venue' => $sessionParticipant->session?->venue,
                    'schedules' => $sessionParticipant->session?->schedules,
                    'status' => $sessionParticipant->status,
                    'is_approved' => $sessionParticipant->is_approved,
                    'attended_at' => $sessionParticipant->attended_at,
                    'created_at' => $sessionParticipant->created_at,
                ];
            }),
        ];
    }
}
