<?php

namespace App\Services\Post;

use App\Models\PostComment;
use App\Models\PostReaction;

class SaveClass
{
    public function react($request, $postId)
    {
        $userId = \Auth::id();
        $reactionId = $request->reaction_id;

        $existing = PostReaction::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($existing && (int) $existing->reaction_id === (int) $reactionId) {
            $existing->delete();
            $myReaction = null;
        } elseif ($existing) {
            $existing->update(['reaction_id' => $reactionId]);
            $myReaction = ['id' => $existing->reaction_id, 'name' => $existing->reaction->name];
        } else {
            $existing = PostReaction::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'reaction_id' => $reactionId,
            ]);
            $myReaction = ['id' => $existing->reaction_id, 'name' => $existing->reaction->name];
        }

        return [
            'reactions_count' => PostReaction::where('post_id', $postId)->count(),
            'my_reaction' => $myReaction,
        ];
    }

    public function comment($request, $postId)
    {
        $comment = PostComment::create([
            'post_id' => $postId,
            'user_id' => \Auth::id(),
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
        ]);

        return $comment->load('user.profile');
    }
}
