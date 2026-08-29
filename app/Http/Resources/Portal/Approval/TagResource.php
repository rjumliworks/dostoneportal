<?php

namespace App\Http\Resources\Portal\Approval;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad', 10);

        $position = $this->user->organization->position ?? null;

        return [
            'id' => $hashids->encode($this->id),
            'name' => $this->user->profile->firstname.' '.$this->user->profile->lastname,
            'division' => $this->division_id,
            'is_joined' => (bool) $this->is_joined,
            'position' => $position->name ?? 'n/a',
            'is_regular' => (bool) ($position->is_regular ?? false),
            'salary' => (float) ($position?->salary?->getRawOriginal('amount') ?? 0),
            'avatar' => ($this->user->profile && $this->user->profile->avatar && $this->user->profile->avatar !== 'noavatar.jpg')
            ? asset($this->user->profile->avatar)
            : asset('images/avatars/avatar.jpg'),
        ];
    }
}
