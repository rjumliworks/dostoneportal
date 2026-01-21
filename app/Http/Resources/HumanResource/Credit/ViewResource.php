<?php

namespace App\Http\Resources\HumanResource\Credit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'profile' => $this->profile,
            'organization' => $this->organization,
            'created_at' => $this->created_at,
            'credits' => $this->credits,
            'avatar' => ($this->profile && $this->profile->avatar && $this->profile->avatar !== 'noavatar.jpg')
            ? asset('storage/' . $this->profile->avatar) 
            : asset('images/avatars/avatar.jpg'), 
        ];
    }
}
