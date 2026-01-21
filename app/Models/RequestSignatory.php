<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestSignatory extends Model
{
    protected $fillable = [
        'code',
        'is_completed',
        'is_disapproved',
        'is_approval_only',
        'disapproved_id',
        'approved_id',
        'approved_date',
        'approved_by',
        'recommended_id',
        'recommended_date',
        'recommended_by',
        'division_id',
        'request_id',
        'status_id'
    ];

    public function statusable()
    {
        return $this->morphMany('App\Models\RequestStatus', 'statusable');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function recommended()
    {
        return $this->belongsTo('App\Models\OrgSignatorySchedule', 'recommended_id', 'id');
    }

    public function approved()
    {
        return $this->belongsTo('App\Models\OrgSignatorySchedule', 'approved_id', 'id');
    }

    public function disapproved()
    {
        return $this->belongsTo('App\Models\OrgSignatorySchedule', 'disapproved_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'division_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
    }

    public function getRecommendedDateAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getApprovedDateAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
