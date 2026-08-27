<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestEvent extends Model
{
    protected $fillable = [
        'title',
        'status_id',
        'audience_id',
        'mode_id',
        'request_id',
        'user_id',
        'is_host',
        'is_managed'
    ];

    protected $appends = ['created_ago'];

    public function types()
    {
        return $this->belongsToMany('App\Models\ListEvent', 'request_event_types', 'request_event_id', 'type_id');
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
        return \Carbon\Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
