<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVoluntaryWork extends Model
{
    protected $fillable = [
        'organization',
        'start_at',
        'end_at',
        'hours',
        'position_nature',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
