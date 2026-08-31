<?php

namespace App\Http\Resources\Trace\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->user->profile->name,
            'status' => $this->status,
            'is_joined' => (bool) $this->is_joined,
            'position' => $this->user->organization->position->name,
            'division_id' => $this->user->organization->division->id,
            'division' => $this->user->organization->division->name,
            'unit' => $this->user->organization->unit->name,
            'avatar' => $this->user->profile?->avatar,
        ];
    }
}
