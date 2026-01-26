<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestComment extends Model
{
    protected $fillable = [
        'request_id',
        'user_id',
        'content',
        'commentable_id',   // <-- must be here
        'commentable_type',
    ];

    protected $appends = ['created_ago'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function replies(): HasMany
    {
        return $this->hasMany(RequestComment::class, 'commentable_id')
                    ->where('commentable_type', self::class)
                    ->with('replies'); // recursive
    }

    public function getCreatedAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
