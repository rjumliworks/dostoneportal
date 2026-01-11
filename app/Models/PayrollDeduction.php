<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    protected $fillable = ['amount','deduction_id','payroll_id','is_plus'];

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = trim(str_replace(',','',$value),'₱');
    }

    public function getAmountAttribute($value)
    {
        return '₱'.number_format($value,2,'.',',');
    }

    public function deduction()
    {
        return $this->belongsTo('App\Models\ListDeduction', 'deduction_id', 'id');
    }

    public function payroll()
    {
        return $this->belongsTo('App\Models\Payroll', 'payroll_id', 'id');
    }
}
