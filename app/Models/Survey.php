<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['reports','year','is_active','is_completed','semester_id','user_id','finished_at'];

    public function answers()
    {
        return $this->hasMany('App\Models\SurveyAnswer', 'survey_id');
    }

    public function semester()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'semester_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('F d, Y g:i a', strtotime($value));
    }

    public function getFinishedAtAttribute($value)
    {
        return ($value) ? date('F d, Y g:i a', strtotime($value)) : '-';
    }
}
