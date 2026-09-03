<?php

namespace App\Http\Requests\Assets;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceRequestRequest extends FormRequest
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
            'maintainable_type' => ['required', 'string', 'in:equipment,vehicle,building'],
            'maintainable_id' => ['required', 'integer'],
            'requested_by' => ['required', 'integer', 'exists:users,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'work_requested' => ['required', 'string'],
            'problem_description' => ['nullable', 'string'],
            'priority_id' => ['nullable', 'integer', 'exists:list_data,id'],
            'status_id' => ['nullable', 'integer', 'exists:list_statuses,id'],
            'remarks' => ['nullable', 'string'],
            'requested_at' => ['required', 'date'],
        ];
    }
}
