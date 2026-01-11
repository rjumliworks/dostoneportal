<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollCutoff extends Model
{
    protected $fillable = ['code','start','end','type','batch','is_locked','cycle_id','user_id','status_id'];

    public function cycle()
    {
        return $this->belongsTo('App\Models\PayrollCycle', 'cycle_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function payrolls()
    {
        return $this->hasMany('App\Models\Payroll', 'cutoff_id');
    }

    public function getStartAttribute($value)
    {
        return date('M d, Y', strtotime($value));
    }

    public function getEndAttribute($value)
    {
        return date('F d, Y', strtotime($value));
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
