<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'year' => $this->year,
            'start' => $this->start,
            'end' => $this->end,
            'detail' => new DetailResource($this->detail),
            'due_at' => $this->due_at,
            'scope' => $this->registration_scope,
            'is_active' => $this->is_active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
