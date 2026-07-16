<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExhibitorViewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'institution' => $this->institution,
            'description' => $this->description,
            'area' => $this->area,
            'is_active' => $this->is_active,
            'has_visited' => $this->has_visited,
            'has_voted' => $this->has_voted,
            'contact' => $this->contact,
            'feedbacks' => FeedbackResource::collection($this->feedbacks),
            'feedback' => new FeedbackResource($this->feedback),
        ];
    }
}
