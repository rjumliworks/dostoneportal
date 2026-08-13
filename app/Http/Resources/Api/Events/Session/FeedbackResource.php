<?php

namespace App\Http\Resources\Api\Events\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rate' => $this->rate,
            'comment' => $this->comment,
            'name' => $this->display_name,
            'avatar' => $this->display_avatar,
            'session_id' => $this->feedbackable_id,
            'participant_id' => $this->participant_id,
            'created_at' => $this->created_at 
        ];
    }
}
