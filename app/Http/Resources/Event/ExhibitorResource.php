<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExhibitorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'code' => $this->code,
            'title' => $this->title,
            'visitors' => $this->visitors_count,
            'votes' => $this->voted_count,
            'institution' => $this->institution,
            'is_active' => $this->is_active
        ];
    }
}
