<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorFace extends Model
{
    protected $fillable = [
        'visitor_id',
        'path',
        'mime_type',
        'face_id',
        'image_id'
    ];

    public function visitor()
    {
        return $this->belongsTo('App\Models\Visitor', 'visitor_id', 'id');
    }
}
