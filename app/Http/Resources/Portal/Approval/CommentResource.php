<?php

namespace App\Http\Resources\Portal\Approval;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'content' => $this->content,
            'name' => $this->user->profile->name,
            'avatar' => ($this->user->profile && $this->user->profile->avatar && $this->user->profile->avatar !== 'noavatar.jpg')
            ? asset('storage/' . $this->user->profile->avatar) 
            : asset('images/avatars/avatar.jpg'), 
            'replies' => CommentResource::collection($this->replies),
            'created_at' => $this->created_at
        ];
    }
}
