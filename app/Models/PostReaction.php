<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostReaction extends Model
{
    protected $fillable = ['reaction_id', 'user_id', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reaction()
    {
        return $this->belongsTo(ListData::class, 'reaction_id');
    }
}
