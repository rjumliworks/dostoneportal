<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorFace extends Model
{
    protected $fillable = [
        'visitor_id',
        'path',
        'name',
        'mime_type',
        'size',
        'face_id',
        'image_id'
    ];

    public function visitor()
    {
        return $this->belongsTo('App\Models\Visitor', 'visitor_id', 'id');
    }
}
