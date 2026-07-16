<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCsfQuestion extends Model
{
    protected $fillable = [
        'name',
        'sequence',
        'is_overall',
        'is_rating',
        'is_active'
    ];

    public function ratings()
    {
        return $this->hasMany('App\Models\EventCsfRating', 'question_id');
    } 

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('F d, Y g:i a', strtotime($value));
    }
}
