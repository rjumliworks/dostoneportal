<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantPointLog extends Model
{
    protected $fillable = [
        'points', 'remarks', 'type_id','point_id'
    ];

    public function engageable()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'type_id', 'id');
    }

    public function point()
    {
        return $this->belongsTo('App\Models\ParticipantPoint', 'point_id', 'id');
    }
}
