<?php

namespace App\Http\Resources\Api\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->participant_id,
            'session_id' => $this->session_id,
            'question' => $this->question,
            'name' => $this->participant->firstname.' '.$this->participant->lastname,
            'avatar' =>  ($this->participant->detail->avatar != 'avatar.jpg') ? asset('storage/'.$this->participant->detail->avatar) : asset('images/avatars/'.$this->participant->detail->avatar),
            'created_at' => $this->created_at 
        ];
    }
}
