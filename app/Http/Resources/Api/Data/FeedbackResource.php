<?php

namespace App\Http\Resources\Api\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'rate' => $this->rate,
            'id' => $this->feedbackable_id,
            'participant_id' => $this->participant->id,
            'comment' => $this->comment,
            'name' => $this->participant->firstname.' '.$this->participant->lastname,
            'avatar' =>  ($this->participant->detail->avatar != 'noavatar.jpg') ? $this->participant->detail->avatar : asset('images/avatars/'.$this->participant->detail->avatar),
            'created_at' => $this->created_at 
        ];
    }
}
