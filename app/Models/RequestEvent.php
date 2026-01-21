<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestEvent extends Model
{
    protected $fillable = [
        'title',
        'audience_id',
        'mode_id',
        'type_id',
        'request_id',
        'is_host'
    ];

    protected $appends = ['created_ago'];

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function mode()
    {
        return $this->belongsTo('App\Models\ListData', 'mode_id', 'id');
    }

    public function audience()
    {
        return $this->belongsTo('App\Models\ListData', 'audience_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
    }

    public function getCreatedAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
