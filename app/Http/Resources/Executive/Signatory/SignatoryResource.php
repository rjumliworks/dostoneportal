<?php

namespace App\Http\Resources\Executive\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignatoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'is_oic' => $this->is_oic,
            'user' => new ProfileResource($this->user),
            'oic' => new ProfileResource($this->oic),
            'schedules' => ScheduleResource::collection($this->schedules)
        ];
    }
}
