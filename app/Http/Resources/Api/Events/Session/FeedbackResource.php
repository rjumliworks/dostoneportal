<?php

namespace App\Http\Resources\Api\Events\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
            'rate' => $this->rate,
            'comment' => $this->comment,
            'name' => $this->participant->name,
            'avatar' => $this->participant->detail->avatar,
            'session_id' => $this->feedbackable_id,
            'participant_id' => $this->participant_id,
            'created_at' => $this->created_at 
        ];
    }
}
