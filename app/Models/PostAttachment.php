<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    protected $fillable = ['path', 'mime_type', 'size', 'text', 'meta', 'kind_id', 'post_id'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function kind()
    {
        return $this->belongsTo(ListData::class, 'kind_id');
    }
}
