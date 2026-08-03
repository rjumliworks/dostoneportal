<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vip extends Model
{
    protected $fillable = ['name', 'designation', 'affiliation', 'avatar', 'face_id', 'image_id'];

    public function getAvatarAttribute($value)
    {
        if ($value === 'noavatar.jpg') {
            return asset('images/avatars/' . $value);
        }

        return Storage::disk('s3')->url($value) . '?v=' . ($this->updated_at?->timestamp ?? time());
    }
}
