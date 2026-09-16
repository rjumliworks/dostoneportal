<?php

namespace App\Http\Resources\Trace\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignatoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_oic' => $this->is_oic,
            'user' => $this->user ? (new ProfileResource($this->user))->resolve() : null,
            'oic' => $this->oic ? (new ProfileResource($this->oic))->resolve() : null,
            'schedules' => ScheduleResource::collection($this->schedules)->resolve(),
            'oic_history' => $this->whenLoaded('oicHistory', fn () => ScheduleResource::collection($this->oicHistory)->resolve())
        ];
    }
}
