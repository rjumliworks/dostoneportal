<?php

namespace App\Http\Resources\Event\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'session_id' => $this->session_id,
            'question' => $this->question,
            'is_answered' => $this->is_answered,
            'participant' => new ParticipantResource($this->participant),
            'created_at' => $this->created_at
        ];
    }
}
