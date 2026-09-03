<?php

namespace App\Http\Requests\Assets;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceRecordRequest extends FormRequest
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
            'request_id' => ['nullable', 'integer', 'exists:asset_maintenance_requests,id'],
            'type_id' => ['nullable', 'integer', 'exists:list_data,id'],
            'status_id' => ['nullable', 'integer', 'exists:list_statuses,id'],
            'date' => ['required', 'date'],
            'operation_performed' => ['required', 'string'],
            'remarks' => ['nullable', 'string'],
            'performed_by' => ['required', 'integer', 'exists:users,id'],
            'cost' => ['nullable', 'numeric'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
            'next_due' => ['nullable', 'date'],
        ];
    }
}
