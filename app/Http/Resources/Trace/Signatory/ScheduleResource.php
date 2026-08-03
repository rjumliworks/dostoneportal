<?php

namespace App\Http\Resources\Trace\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->user ? new ProfileResource($this->user) : null,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'is_designated' => $this->is_designated,
            'is_ongoing' => $this->is_ongoing,
            'is_completed' => $this->is_completed
        ];
    }
}
