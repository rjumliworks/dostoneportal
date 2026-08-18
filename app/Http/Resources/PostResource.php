<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'user' => [
                'name' => $this->user?->profile?->name,
                'avatar' => $this->user?->profile?->avatar,
            ],
            'comments_count' => $this->comments_count,
            'reactions_count' => $this->reactions_count,
            'my_reaction' => $this->whenLoaded('reactions', function () {
                $mine = $this->reactions->first();
                return $mine ? ['id' => $mine->reaction_id, 'name' => $mine->reaction?->name] : null;
            }),
            'posted_at' => $this->created_at?->diffForHumans(),
        ];
    }
}
