<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestOvertime extends Model
{
    protected $fillable = [
        'code',
        'earned',
        'targets',
        'status_id',
        'request_id'
    ];

    protected $casts = [
        'targets' => 'array',
    ];

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
