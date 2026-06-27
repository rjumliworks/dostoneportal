<?php

namespace App\Http\Resources\Portal\Whereabouts;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($this->id);

        return [
            'id' => $this->id,
            'code' => $code,
            'email' => $this->email,
            'username' => $this->username,
            'is_active' => $this->is_active,
            'is_locked' => $this->is_locked,
            'avatar' => $this->profile?->avatar,
            'name' => $this->profile->name,
            'fullname' => $this->profile->fullname,
            'profile' => new ProfileResource($this->profile),
            'mobile' => $this->profile->mobile,
            'birthdate' => $this->profile->birthdate,
            'organization' => $this->organization,
            'created_at' => $this->created_at
        ];
    }
}
