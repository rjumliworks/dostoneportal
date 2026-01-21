<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RequestLeave extends Model
{
    use LogsActivity;

    protected $fillable = [
        'count',
        'pay',
        'nopay',
        'borrowed',
        'detail_id',
        'type_id',
        'request_id',
        'certified_by',
        'certified_id',
        'certified_date'
    ];

    protected $casts = [
        'count' => 'float',
        'pay' => 'float',
        'nopay' => 'float',
    ];

    public function certified()
    {
        return $this->belongsTo('App\Models\OrgSignatorySchedule', 'recommended_id', 'id');
    }

    public function credits()
    {
        return $this->hasMany('App\Models\RequestLeaveCredit', 'leave_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListLeave', 'type_id', 'id');
    }

    public function detail()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'detail_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
    }

    public function getCountAttribute($value)
    {
        return fmod($value, 1) == 0 ? (int)$value : (float)$value;
    }

    public function getNopayAttribute($value)
    {
        return fmod($value, 1) == 0 ? (int)$value : (float)$value;
    }

    public function getPayAttribute($value)
    {
        return fmod($value, 1) == 0 ? (int)$value : (float)$value;
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    protected static $expenseLabels = [
        1 => 'Accommodation (Actual)',
        2 => 'Accommodation (Per Diem)',
        3 => 'Incidental Expenses',
        4 => 'Meals',
    ];

    public function getExpenseItemsAttribute()
    {
        return collect($this->expenses)->map(function ($id) {
            return [
                'id' => (int) $id,
                'name' => self::$expenseLabels[$id] ?? 'Unknown',
            ];
        });
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
            'mode_id',
            'expense_id',
            'request_id',
            'expenses'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Travel Order')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
