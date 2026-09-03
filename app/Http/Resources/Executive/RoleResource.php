<?php

namespace App\Http\Resources\Executive;

use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($this->id);

        return [
            'code' => $code,
            'name' => $this->role->name,
            'added' => $this->added?->profile->fullname,
            'removed' => ($this->removed) ? $this->removed?->profile->fullname : '-',
            'removed_at' => $this->removed_at,
            'removed_at_raw' => $this->getRawOriginal('removed_at') ? Carbon::parse($this->getRawOriginal('removed_at'))->toIso8601String() : null,
            'created_at' => $this->created_at,
            'is_active' => $this->is_active
        ];
    }
}
