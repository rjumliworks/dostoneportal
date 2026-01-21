<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $fillable = ['rating','question_id','survey_id','user_id'];

    public function survey()
    {
        return $this->belongsTo('App\Models\Survey', 'survey_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\SurveyQuestion', 'question_id', 'id');
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
}
