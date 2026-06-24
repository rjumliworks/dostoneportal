<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListEvent extends Model
{
    protected $fillable = [
        'name',
        'type',
        'color',
        'bg',
        'icon',
        'fields',
        'is_active'
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    public function getFieldsAttribute($value)
    {
        $fields = json_decode($value, true) ?? [];

        return array_merge($this->defaultFields(), $fields);
    }

    public function defaultFields()
    {
        return [
            'title' => false,
            'info' => false,
            'venue' => false,
            'purpose' => false,
            'customer' => false,
            'samples' => false,
            'conforme' => false,
            'quotation' => false,
            'tsr' => false,
            'employees' => true,
        ];
    }

    public function hasField($key)
    {
        return $this->fields[$key] ?? false;
    }
}
