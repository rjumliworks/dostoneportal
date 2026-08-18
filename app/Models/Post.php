<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'number', 'title', 'content', 'is_commentable', 'type_id', 'visibility_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(ListData::class, 'type_id');
    }

    public function visibility()
    {
        return $this->belongsTo(ListData::class, 'visibility_id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function reactions()
    {
        return $this->hasMany(PostReaction::class);
    }

    public function attachments()
    {
        return $this->hasMany(PostAttachment::class);
    }
}
