<?php

namespace App\Http\Resources\Trace\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user->profile->firstname.' '.$this->user->profile->lastname,
            'status' => $this->status,
            'position' => $this->user->organization->position->name,
            'division_id' => $this->user->organization->division->id,
            'division' => $this->user->organization->division->name,
            'unit' => $this->user->organization->unit->name,
            'avatar' => ($this->user->profile && $this->user->profile->avatar && $this->user->profile->avatar !== 'noavatar.jpg')
            ? asset('storage/' . $this->user->profile->avatar) 
            : asset('images/avatars/avatar.jpg'), 
        ];
    }
}
