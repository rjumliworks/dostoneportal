<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEligibility extends Model
{
    protected $fillable = [
        'exam_name',
        'rating',
        'exam_at',
        'exam_place',
        'license_number',
        'license_valid_until',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
