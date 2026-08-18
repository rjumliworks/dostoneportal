<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCommentResource;

class ViewClass
{
    public function show($id)
    {
        $post = Post::with([
            'user.profile',
            'reactions' => fn ($q) => $q->where('user_id', \Auth::id())->with('reaction'),
            'comments' => fn ($q) => $q->whereNull('parent_id')
                ->with(['user.profile', 'replies.user.profile'])
                ->orderBy('created_at'),
        ])
        ->withCount(['comments', 'reactions'])
        ->findOrFail($id);

        return [
            'post' => new PostResource($post),
            'comments' => PostCommentResource::collection($post->comments),
        ];
    }
}
