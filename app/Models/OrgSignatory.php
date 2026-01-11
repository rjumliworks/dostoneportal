<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgSignatory extends Model
{
    protected $fillable = ['user_id','oic_id','is_oic','is_topmanagement','is_active'];

    public function designationable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

     public function oic()
    {
        return $this->belongsTo('App\Models\User', 'oic_id', 'id');
    }

    public function schedules()
    {
        return $this->hasMany('App\Models\OrgSignatorySchedule', 'signatory_id');
    }
}
