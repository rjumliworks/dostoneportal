<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'parent_id' => $this->parent_id,
            'comment' => $this->comment,
            'user' => [
                'name' => $this->user?->profile?->name,
                'avatar' => $this->user?->profile?->avatar,
            ],
            'posted_at' => $this->created_at?->diffForHumans(),
            'replies' => PostCommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
