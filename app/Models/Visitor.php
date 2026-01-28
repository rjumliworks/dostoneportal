<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['name','affiliation_id','designation_id','type_id','status_id'];

    protected static function booted()
    {
        static::creating(function ($visitor) {
            $visitor->username = self::generateUniqueUsername();
        });
    }

    private static function generateUniqueUsername()
    {
        $prefix = now()->format('mY'); // MMYYYY

        do {
            $random = strtoupper(Str::random(6)); // H1K23S
            $username = "{$prefix}-{$random}";
        } while (self::where('username', $username)->exists());

        return $username;
    }

    public function affiliation()
    {
        return $this->belongsTo('App\Models\ListName', 'affiliation_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo('App\Models\ListName', 'designation_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function faces()
    {
        return $this->hasMany('App\Models\VisitorFace', 'visitor_id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\VisitorLogs', 'visitor_id');
    }
}
