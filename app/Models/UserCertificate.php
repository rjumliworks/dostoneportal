<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCertificate extends Model
{
    protected $fillable = [
        'file', 
        'password',
        'expires_at',
        'signature',
        'user_id'
    ];

    protected $hidden = [
        'password'
    ];

    public function user()     { 
        return $this->belongsTo(User::class); 
    }
}
