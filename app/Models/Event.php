<?php

namespace App\Models;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
       'code',
       'name',
       'year',
       'start',
       'end',
       'is_active',
       'user_id'
    ];

    protected $appends = ['reference'];
    public function getReferenceAttribute(): string
    {
        return (new Hashids('krad', 10))->encode($this->id);
    }


    public function feedbackable()
    {
        return $this->morphOne('App\Models\CsfEntry', 'feedbackable');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function getStartAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }

    public function detail()
    {
        return $this->hasOne('App\Models\EventDetail', 'event_id');
    } 

    public function venues()
    {
        return $this->hasMany('App\Models\EventVenue', 'event_id');
    }

    public function sessions()
    {
        return $this->hasMany('App\Models\EventSession', 'event_id');
    }

    public function exhibitors()
    {
        return $this->hasMany('App\Models\EventExhibitor', 'event_id');
    }

    public function getEndAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }
}
