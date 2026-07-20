<?php

namespace App\Http\Resources\Event\Exhibitor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'reference' => $this->reference,
            'institution' => $this->institution,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'contact' => $this->contact,
            'event' => $this->event,
            'visitors' => VisitorResource::collection($this->visitors),
            'voters' => VoterResource::collection(
                $this->visitors->where('has_voted', true)->values()
            ),
            'feedbacks' => FeedbackResource::collection($this->feedbackable),
        ];
    }
}
