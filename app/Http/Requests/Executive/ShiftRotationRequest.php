<?php

namespace App\Http\Requests\Executive;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShiftRotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('shift_rotations', 'user_id')->ignore($this->id),
            ],
            'order' => ['required', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a guard.',
            'user_id.unique' => 'This guard is already part of the rotation.',
            'order.required' => 'Please set the rotation order.',
        ];
    }
}
