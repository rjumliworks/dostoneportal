<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    protected $fillable = ['name', 'designation', 'affiliation', 'avatar', 'face_id', 'image_id'];
}
