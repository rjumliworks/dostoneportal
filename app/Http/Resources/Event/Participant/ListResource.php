<?php

namespace App\Http\Resources\Event\Participant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'avatar' => $this->detail?->avatar,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'type' => $this->detail?->type,
            'affiliation' => $this->detail?->affiliation,
            'others' => $this->detail?->others,
            'designation' => $this->detail?->designation,
            'is_completed' => $this->detail?->is_completed,
            'sessions_count' => $this->sessions_count,
            'created_at' => $this->created_at,
        ];
    }
}
