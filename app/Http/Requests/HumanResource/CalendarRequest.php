<?php

namespace App\Http\Requests\HumanResource;

use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required',
            'title' => 'required',
            'venue' => [
                function ($attribute, $value, $fail) {
                    if (!in_array($this->type, ['Holiday', 'Leave']) && empty($value)) {
                        $fail('The venue field is required');
                    }
                },
            ],
            'description' => [
                function ($attribute, $value, $fail) {
                    if ($this->type !== 'Holiday' && empty($value)) {
                        $fail('The description field is required.');
                    }
                },
            ],
        ];
    }
}
