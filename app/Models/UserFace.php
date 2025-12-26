<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFace extends Model
{
    protected $fillable = [
        'user_id',
        'file_id',
        'face_id',
        'image_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\UserFolderFile', 'file_id', 'id');
    }
}
