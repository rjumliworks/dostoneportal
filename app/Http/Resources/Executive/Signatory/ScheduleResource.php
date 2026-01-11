<?php

namespace App\Http\Resources\Executive\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => new ProfileResource($this->user),
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'is_ongoing' => $this->is_ongoing,
            'is_completed' => $this->is_completed
        ];
    }
}
