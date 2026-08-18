<?php

namespace App\Services\Post;

use App\Models\Post;

class PrintClass
{
    public function order($id)
    {
        $post = Post::with('user.profile')->findOrFail($id);

        $pdf = \PDF::loadView('prints.special-order', [
            'post' => $post,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('DOST-Special-Order-No-' . ($post->number ?? $post->code) . '.pdf');
    }
}
