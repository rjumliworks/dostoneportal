<?php

namespace App\Http\Requests\Portal\Approval;

use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'sometimes|required',
            'request_id' => 'sometimes|required',
            'status_id' => 'sometimes|required',
            'type' => 'sometimes|required',
            'photo' => 'sometimes|required',
            'reason' => 'sometimes|required_if:status_id,30',
        ];
    }
}
