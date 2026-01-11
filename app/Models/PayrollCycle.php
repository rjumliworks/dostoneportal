<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollCycle extends Model
{
    protected $fillable = ['code','month','year','is_regular','is_locked','user_id','payroll_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function payroll()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'payroll_id', 'id');
    }

    public function cutoffs()
    {
        return $this->hasMany('App\Models\PayrollCutoff', 'cycle_id');
    }

    public function getMonthAttribute() 
    {
        return \Carbon\Carbon::create()->month($this->attributes['month'])->format('F');
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
