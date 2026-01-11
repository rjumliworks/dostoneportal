<?php

namespace App\Http\Resources\Executive\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'avatar' => ($this?->user?->profile && $this?->user?->profile->avatar && $this?->user?->profile->avatar !== 'noavatar.jpg')
            ? asset('storage/' . $this?->user->profile->avatar) 
            : asset('images/avatars/avatar.jpg'), 
            'order' => $this->order,
            'designation' => $this->designation->name,
            'assigned' => $this->assigned,
            'user' => $this->user,
            'is_oic' => $this->is_oic,
            'is_active' => $this->is_active,
        ];
    }
}
