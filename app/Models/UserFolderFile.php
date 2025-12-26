<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFolderFile extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'size',
        'folder_id',
        'opened_at',
        'meta',
        'status',
        'type_id'
    ];

    protected $casts = ['meta' => 'array'];
    protected $dates = ['deleted_at'];

    public function folder()
    {
        return $this->belongsTo('App\Models\UserFolder', 'folder_id', 'id');
    }

    public function faces()
    {
        return $this->hasMany('App\Models\UserFace', 'file_id')->orderBy('updated_at','DESC');
    }

    public function getOpenedAtAttribute($value)
    {
        return ($value) ? date('F d, Y g:i a', strtotime($value)) : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('F d, Y g:i a', strtotime($value));
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
        ->logOnly([
            'name',
            'path',
            'mime_type',
            'size',
            'folder_id',
            'opened_at',
            'meta',
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('File')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
