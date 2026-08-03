<?php

namespace App\Http\Resources\Trace\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'order' => $this->order,
            'designation' => $this->designation->name,
            'assigned' => $this->assigned,
            'avatar' => $this->is_oic
                ? ($this->oic?->profile?->avatar ?? asset('images/avatars/avatar.jpg'))
                : ($this->user?->profile?->avatar ?? asset('images/avatars/avatar.jpg')),
            'user' => $this->user ? new ProfileResource($this->user) : null,
            'oic' => $this->oic ? new ProfileResource($this->oic) : null,
            'is_oic' => $this->is_oic,
            'is_active' => $this->is_active,
            'signatory_id' => $this->designationable?->id,
            'schedules' => ScheduleResource::collection($this->designationable->schedules),
            'updated_at' => $this->updated_at
        ];
    }
}
