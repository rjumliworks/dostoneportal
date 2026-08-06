<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReference extends Model
{
    protected $fillable = [
        'name',
        'address',
        'contact',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
