<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantFace extends Model
{
    protected $fillable = [
        'participant_id',
        'face_id',
        'image_id',
        'status',
    ];

    public function participant()
    {
        return $this->belongsTo('App\Models\ParticipantDetail', 'participant_id', 'id');
    }
}
