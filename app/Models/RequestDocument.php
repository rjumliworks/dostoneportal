<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDocument extends Model
{
    protected $fillable = [
        'data',
        'path',
        'approved_id',
        'approved_date',
        'approved_by',
        'type_id',
        'request_id',
        'status_id'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function approved()
    {
        return $this->belongsTo('App\Models\OrgSignatorySchedule', 'approved_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
    }
}
