<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserCredit extends Model
{
    use LogsActivity;

    protected $fillable = ['balance','earned','used','carried_over','expired','year','leave_id','user_id','is_active'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function leave()
    {
        return $this->belongsTo('App\Models\ListLeave', 'leave_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\CreditLog', 'credit_id');
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
        ->logOnly(['balance','earned','used','carried_over','year','leave_id','user_id'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Credit')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
