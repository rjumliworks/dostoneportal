<?php

namespace App\Http\Requests\Trace;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:request_events,id',
            'title' => 'sometimes|required',
            'purpose' => 'sometimes|required',
            'types' => 'sometimes|required|array|min:1',
            'types.*' => 'integer|exists:list_events,id',
            'mode_id' => 'sometimes|required|integer',
            'audience_id' => 'sometimes|required|integer',
            'date' => 'sometimes|required',
            'is_host' => 'sometimes|required',
            'is_managed' => 'sometimes|required|boolean',
            'time' => 'sometimes|required',
            'address' => 'sometimes|string|max:200',
            'region_code' => 'sometimes|required',
            'province_code' => 'sometimes|required',
            'municipality_code' => 'sometimes|required',
            'barangay_code' => 'sometimes|required',
            'longitude' => 'sometimes|required',
            'latitude' => 'sometimes|required',
            'date_type' => 'sometimes|required',
            'dates' => 'sometimes|required|array|min:1',
            'dates.*.date' => 'required|date',
            'dates.*.timeOfDay' => 'required|string',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->date_type !== 'Range') {
                return;
            }

            $dates = $this->dates ?? [];
            if (count($dates) < 2) {
                return;
            }

            $start = $dates[0]['date'] ?? null;
            $end = $dates[count($dates) - 1]['date'] ?? null;

            if ($start && $end && $start > $end) {
                $validator->errors()->add('dates', 'The start date must not be after the end date.');
            }
        });
    }
}
