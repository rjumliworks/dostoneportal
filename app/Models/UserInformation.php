<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class UserInformation extends Model
{
    use LogsActivity;

    protected $fillable = ['accounts','contacts','backgrounds','barangay_code','user_id'];

    protected $casts = [
        'accounts' => 'encrypted:json', 
        'contacts' => 'encrypted:json', 
        'backgrounds' => 'encrypted:json', 
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['accounts','contacts','backgrounds'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the user information")
        ->useLogName('User Information')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
