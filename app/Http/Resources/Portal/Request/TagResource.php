<?php

namespace App\Http\Resources\Portal\Request;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user->profile->firstname.' '.$this->user->profile->lastname,
            'division' => $this->division_id,
            'avatar' => ($this->user->profile && $this->user->profile->avatar && $this->user->profile->avatar !== 'noavatar.jpg')
            ? asset('storage/' . $this->user->profile->avatar) 
            : asset('images/avatars/avatar.jpg'), 
        ];
    }
}
