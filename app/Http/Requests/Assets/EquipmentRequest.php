<?php

namespace App\Http\Requests\Assets;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'old_code' => ['nullable', 'string', 'max:30'],
            'type_id' => ['required', 'integer', 'exists:list_data,id'],
            'station_id' => ['nullable', 'integer', 'exists:list_dropdowns,id'],
            'status_id' => ['required', 'integer', 'exists:list_statuses,id'],
            'maintenance_plan' => ['nullable', 'string', 'max:255'],
            'maintenance_due' => ['nullable', 'date'],
            'maintenance_schedule' => ['nullable', 'array'],
            'maintenance_schedule.*' => ['integer', 'between:1,12'],
            'remarks' => ['nullable', 'string'],
            'acquired_at' => ['nullable', 'date'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'specification' => ['nullable', 'array'],
            'specification.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
