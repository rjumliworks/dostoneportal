<?php

namespace App\Http\Requests\Assets;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
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
            'plate' => ['required', 'string', 'max:255'],
            'type_id' => ['required', 'integer', 'exists:list_data,id'],
            'status_id' => ['required', 'integer', 'exists:list_statuses,id'],
            'station_id' => ['required', 'integer', 'exists:list_dropdowns,id'],
            'acquired_at' => ['nullable', 'date'],
        ];
    }
}
