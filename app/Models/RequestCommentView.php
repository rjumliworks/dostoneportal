<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestCommentView extends Model
{
    protected $fillable = [
        'viewed',
        'viewed_at',
        'user_id',
        'comment_id'
    ];

    public function comment()
    {
        return $this->belongsTo('App\Models\RequestComments', 'comment_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
