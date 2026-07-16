<?php

namespace App\Http\Resources\Api\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExhibitorResource extends JsonResource
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
            'type' => $this->type,
            'is_active' => $this->is_active,
            'has_visited' => $this->has_visited,
            'has_voted' => $this->has_voted,
            'has_feedback' => $this->has_feedback,
            'contact' => $this->contact,
            'feedbackable' => FeedbackResource::collection($this->feedbackable),
        ];
    }
}
