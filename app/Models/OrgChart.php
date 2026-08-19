<?php

namespace App\Models;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OrgChart extends Model
{
    use LogsActivity;

    protected $appends = ['reference'];
    protected $fillable = ['user_id','oic_id','is_oic','is_active'];

    public function getReferenceAttribute(): string
    {
        return (new Hashids('krad', 10))->encode($this->id);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function oic()
    {
        return $this->belongsTo('App\Models\User', 'oic_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'designation_id', 'id');
    }

    public function assigned()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'assigned_id', 'id');
    }

    public function designationable()
    {
        return $this->morphOne('App\Models\OrgSignatory', 'designationable');
    }

    // user_ids exempt from attendance checking, lates, absences, and whereabouts reporting
    // designation_id 43 = Regional Director (see LeaveClass::signatory())
    public static function excludedFromAttendance(): array
    {
        return static::where('designation_id', 43)->where('is_active', 1)->pluck('user_id')->filter()->values()->all();
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['user_id','oic_id','is_oic','is_active'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the user information")
        ->useLogName('User')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
