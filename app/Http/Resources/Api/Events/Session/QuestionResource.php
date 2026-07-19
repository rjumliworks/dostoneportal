<?php

namespace App\Http\Resources\Api\Events\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'participant_id' => $this->participant_id,
            'question' => $this->question,
            'is_answered' => $this->is_answered,
            'avatar' => $this->participant->detail->avatar,
            'name' => $this->participant->name,
            'created_at' => $this->created_at
        ];
    }
}
