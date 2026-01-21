<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class RequestTravel extends Model
{
    use LogsActivity;
    
    protected $table = 'request_travels';

    protected $fillable = [
        'code',
        'mode_id',
        'transpo_id',
        'expense_id',
        'request_id',
        'is_ard',
        'expenses'
    ];

    protected $casts = [
        'expenses' => 'array'
    ];

    public function mode()
    {
        return $this->belongsTo('App\Models\ListData', 'mode_id', 'id');
    }
    
    public function transpo()
    {
        return $this->belongsTo('App\Models\ListData', 'transpo_id', 'id');
    }

    public function expense()
    {
        return $this->belongsTo('App\Models\ListData', 'expense_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
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
            'transpo_id',
            'expense_id',
            'request_id',
            'is_ard',
            'expenses'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Travel Order')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
