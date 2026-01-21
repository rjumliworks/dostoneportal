<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CreditLog extends Model
{
    use LogsActivity;

    protected $fillable = ['amount','old_balance','new_balance','remarks','is_automated','is_expired','user_id','type_id','credit_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function credit()
    {
        return $this->belongsTo('App\Models\UserCredit', 'credit_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('F d, Y g:i a', strtotime($value));
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
        ->logOnly(['amount','old_balance','new_balance','remarks','is_automated','is_expired','user_id','type_id','credit_id'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Credit Log')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
