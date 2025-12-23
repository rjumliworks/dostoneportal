<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
    use LogsActivity;

    protected $fillable = ['status_id','type_id','position_id','salary_id','unit_id','division_id','station_id','user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\ListPosition', 'position_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'division_id', 'id');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'station_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\ListUnit', 'unit_id', 'id');
    }

    public function salary()
    {
        return $this->belongsTo('App\Models\ListSalary', 'salary_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['status_id','type_id','position_id','salary_id','unit_id','division_id','station_id','user_id'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the user organization")
        ->useLogName('User Organization')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
