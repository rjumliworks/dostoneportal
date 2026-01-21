<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RequestLeaveCredit extends Model
{
    use LogsActivity;

    protected $fillable = [
        'is_borrowed',
        'is_returned',
        'log_id',
        'credit_id',
        'leave_id'
    ];

    public function log()
    {
        return $this->belongsTo('App\Models\CreditLog', 'log_id', 'id');
    }
    
    public function credit()
    {
        return $this->belongsTo('App\Models\UserCredit', 'credit_id', 'id');
    }

    public function leave()
    {
        return $this->belongsTo('App\Models\RequestLeave', 'leave_id', 'id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
 
    public function updateIfDirty(array $attributes){
        $this->fill($attributes);
        $dirtyAttributes = $this->getDirty();
        if(!empty($dirtyAttributes)) {
            $originalAttributes = array_intersect_key($this->getOriginal(), $dirtyAttributes);
            $updated = $this->update($dirtyAttributes);
            return $updated;
        }
        return false;
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly([
            'is_borrowed',
            'is_returned',
            'log_id',
            'credit_id',
            'leave_id'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Leave Credit')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
