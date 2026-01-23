<?php

namespace App\Http\Resources\Portal\Approval;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user->profile->fullname,
            'status' => $this->status->name,
            'icon' => $this->status->icon,
            'color' => $this->status->color,
            'date' => $this->created_at
        ];
    }
}
