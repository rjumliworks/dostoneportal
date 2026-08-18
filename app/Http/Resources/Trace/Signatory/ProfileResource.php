<?php

namespace App\Http\Resources\Trace\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'avatar' => $this->profile?->avatar ?? asset('images/avatars/avatar.jpg'),
            'name' => $this->profile?->name ?? $this->username,
            'fullname' => $this->profile?->fullname,
            'sex' => $this->profile?->sex?->name,
            'division' => $this->organization?->division?->name,
            'position' => $this->organization?->position?->name,
        ];
    }
}
