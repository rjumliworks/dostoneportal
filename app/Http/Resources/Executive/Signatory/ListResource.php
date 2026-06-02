<?php

namespace App\Http\Resources\Executive\Signatory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_oic' => $this->is_oic,
            'order' => $this->order,
            'designation' => $this->designation->name,
            'assigned' => $this->assigned,
            'user' => new ProfileResource($this->user),
            'oic' => new ProfileResource($this->oic)
        ];
    }
}
