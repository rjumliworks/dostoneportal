<?php

namespace App\Http\Resources\HumanResource\Credit;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($this->id);

        return [
            'code' => $code,
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
