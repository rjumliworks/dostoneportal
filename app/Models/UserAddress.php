<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
     protected $fillable = [
        'user_id',
        'is_permanent', 
        'address',
        'region_code',
        'province_code',
        'municipality_code',
        'barangay_code',
        'latitude',
        'longitude'
    ];
}
