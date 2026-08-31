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
        $idTable = in_array($this->option, ['participant', 'participants']) ? 'requests' : 'request_events';

        return [
            'id' => "nullable|integer|exists:{$idTable},id",
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
            'type' => 'required_if:option,participants|in:all,group',
            'division_id' => 'nullable|integer|exists:list_dropdowns,id',
            'unit_id' => 'nullable|integer|exists:list_units,id',
            'tag_id' => 'required_if:option,remove_participant,join_participant|integer|exists:request_tags,id',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->option === 'participants' && $this->type === 'group' && !$this->division_id && !$this->unit_id) {
                $validator->errors()->add('division_id', 'Please select a division or unit.');
            }

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
