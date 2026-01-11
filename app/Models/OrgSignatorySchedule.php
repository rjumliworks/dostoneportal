<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgSignatorySchedule extends Model
{
    protected $fillable = ['start_at','end_at','signatory_id','user_id','is_ongoing','is_completed','is_designated'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function signatory()
    {
        return $this->belongsTo('App\Models\OrgSignatory', 'signatory_id', 'id');
    }

    public function getStartAtAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }

    public function getEndAtAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }
}
