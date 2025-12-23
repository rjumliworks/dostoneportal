<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeduction extends Model
{
    protected $fillable = ['amount','deduction_id','user_id','is_recurring','is_active','is_automatic'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function deduction()
    {
        return $this->belongsTo('App\Models\ListDeduction', 'deduction_id', 'id');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = trim(str_replace(',','',$value),'₱');
    }

    public function getAmountAttribute($value)
    {
        return '₱'.number_format($value,2,'.',',');
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
