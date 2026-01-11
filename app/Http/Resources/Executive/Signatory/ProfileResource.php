<?php

namespace App\Http\Resources\Executive\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'avatar' => ($this->profile && $this->profile->avatar && $this->profile->avatar !== 'noavatar.jpg')
            ? asset('storage/' .$this->profile->avatar) 
            : asset('images/avatars/avatar.jpg'), 
            'name' => $this->profile->name
        ];
    }
}
