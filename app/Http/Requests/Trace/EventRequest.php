<?php

namespace App\Http\Requests\Trace;

use App\Models\RequestDate;
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
            'title' => 'sometimes|required',
            'purpose' => 'sometimes|required',
            'type_id' => 'sometimes|required|integer',
            'mode_id' => 'sometimes|required|integer',
            'audience_id' => 'sometimes|required|integer',
            'date' => 'sometimes|required',
            'is_host' => 'sometimes|required',
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
            $this->checkDateOverlap($validator);
        });
    }

    protected function checkDateOverlap($validator)
    {
        $userId = $this->user()->id;
        $dates = $this->dates ?? [];

        if (empty($dates)) return;

        // Determine continuous or non-continuous
        if ($this->date_type != 'Multiple Dates (non-continuous)') {
            $datesOnly = array_column($dates, 'date');
            $newStart = min($datesOnly);
            $newEnd = max($datesOnly);

            $hasOverlap = RequestDate::whereHas('request', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->where(function($q) use ($newStart, $newEnd) {
                    $q->where(function($inner) use ($newStart, $newEnd) {
                        $inner->where('start', '<=', $newEnd)
                              ->where('end', '>=', $newStart);
                    });
                })
                ->exists();

            if ($hasOverlap) {
                $validator->errors()->add('dates', 'You already have a request within these dates.');
            }

        } else {
            // Non-continuous mode: check per date
            foreach ($dates as $d) {
                $date = $d['date'];
                $hasOverlap = RequestDate::whereHas('request', function($q) use ($userId) {
                        $q->where('user_id', $userId);
                    })
                    ->where('start', '<=', $date)
                    ->where('end', '>=', $date)
                    ->exists();

                if ($hasOverlap) {
                    $validator->errors()->add('dates', "You already have a request on {$date}.");
                    break;
                }
            }
        }
    }
}
