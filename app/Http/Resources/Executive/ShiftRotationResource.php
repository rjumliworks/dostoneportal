<?php

namespace App\Http\Resources\Executive;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftRotationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'user' => [
                'name' => $this->user?->profile?->name,
                'avatar' => $this->user?->profile?->avatar,
                'position' => $this->user?->organization?->position?->name,
            ],
            'current_shift' => $this->user?->organization?->shift?->name,
        ];
    }
}
