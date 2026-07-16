<?php

namespace App\Http\Resources\Api\Data;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);

        return [
            'id' => $this->id,
            'key' => $key,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'year' => $this->year,
            'start' => $this->start,
            'end' => $this->end,
            'due_at' => $this->due_at,
            'detail' => new DetailResource($this->detail),
            'is_active' => $this->is_active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
