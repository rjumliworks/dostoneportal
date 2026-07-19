<?php

namespace App\Http\Resources\Api\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rate' => $this->rate,
            'session_id' => $this->feedbackable_id,
            'comment' => $this->comment,
            'name' => $this->participant->name,
            'avatar' => $this->participant->detail->avatar,
            'created_at' => $this->created_at 
        ];
    }
}
