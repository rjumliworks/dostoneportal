<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTraining extends Model
{
    protected $fillable = [
        'title',
        'start_at',
        'end_at',
        'hours',
        'type',
        'sponsored_by',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
