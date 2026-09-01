<?php

namespace App\Http\Requests\Portal;

use App\Models\RequestDate;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class MyrequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        switch($this->option){
            case 'cto':
                return [
                    'date_type' => 'sometimes|required',
                    'purpose' => 'sometimes|required',
                    'dates' => 'sometimes|required|array|min:1',
                    'dates.*.date' => 'required|date',
                    'dates.*.timeOfDay' => 'required|string',
                    'document' => 'nullable|mimes:pdf|max:2000'
                ];
            break;
            case 'leave':
                return [
                    'date_type' => 'sometimes|required',
                    'type_id' => 'sometimes|required',
                    'detail_id' => 'sometimes|required',
                    'details' => 'required_if:detail.others,specify illness,specify reason,specify',
                    'dates' => 'sometimes|required|array|min:1',
                    'dates.*.date' => 'required|date',
                    'dates.*.timeOfDay' => 'required|string',
                    // 'document' => 'required_if:type.required_document,1',
                ];
            break;
            case 'event':
                return [
                    'title' => 'required|string|max:255',
                    'type_id' => 'required|integer|exists:list_events,id',
                    'audience_id' => 'required|integer|exists:list_data,id',
                    'mode_id' => 'required|integer|exists:list_data,id',
                    'is_host' => 'sometimes|boolean',
                ];
            break;
            case 'travel':
                return [
                    'events' => 'required|array|min:1',
                    'events.*' => 'integer|exists:request_events,id',
                    'purpose' => 'required|string|max:255',
                    'expenses' => 'required|array|min:1',
                    'tags' => 'required|array|min:1',
                    'expense_id' => 'sometimes|required|integer',
                    'mode_id' => 'sometimes|required|integer',
                    'transpo_id' => 'required_if:mode_id,151',
                    'vehicle' => 'required_if:mode_id,150',
                    'date' => 'sometimes|required',
                    'time' => 'sometimes|required',
                    'remarks' => 'nullable|string',
                    'address' => 'nullable|string|max:200',
                    'region_code' => 'nullable|string',
                    'province_code' => 'nullable|string',
                    'municipality_code' => 'nullable|string',
                    'barangay_code' => 'nullable|string',
                    'longitude' => 'nullable|numeric',
                    'latitude' => 'nullable|numeric',
                    // 'document' => 'nullable|mimes:pdf|max:2000'
                ];
            break;
            case 'reservation':
                return [
                    'event_id' => 'required|integer|exists:request_events,id',
                    'purpose' => 'required|string|max:255',
                    'address' => 'nullable|string|max:200',
                    'region_code' => 'nullable|string',
                    'province_code' => 'nullable|string',
                    'municipality_code' => 'nullable|string',
                    'barangay_code' => 'nullable|string',
                    'longitude' => 'nullable|numeric',
                    'latitude' => 'nullable|numeric',
                    'tags' => 'required|array|min:1',
                    'date' => 'sometimes|required',
                    'time' => 'sometimes|required',
                    'vehicle' => 'sometimes|required',
                    'remarks' => 'nullable|string',
                    'document' => 'nullable|mimes:pdf|max:2000'
                ];
            break;
            case 'training':
                return [
                    'purpose' => 'sometimes|required',
                    'tags' => 'required|array|min:1',
                    'document' => 'nullable|mimes:pdf|max:2000'
                ];
            break;
        }
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

    public function messages()
    {
         switch($this->option){
            case 'leave':
                return [
                    'date_type.required' => 'The date type field is required.',
                    'type_id.required' => 'The type field is required.',
                    'detail_id.required' => 'The detail field is required.',

                    'details.required_if' => 'Details are required when others is set to "Specify Illness", "Specify Reason", or "Specify".',
                    'document.required_if' => 'Document filed is require.',

                    'dates.required' => 'At least one date is required.',
                    'dates.array' => 'Dates must be in a valid list.',
                    'dates.min' => 'You must select at least one date.',

                    'dates.*.date.required' => 'Each date is required.',
                    'dates.*.date.date' => 'Each date must be a valid date.',

                    'dates.*.timeOfDay.required' => 'Each time of day is required.',
                    'dates.*.timeOfDay.string' => 'Each time of day must be a valid string.',
                    
                ];
            break; 
            case 'cto':
                return [
                    'date_type.required' => 'The date type field is required.',
                    'purpose.required' => 'The purpose field is required.',

                    'dates.required' => 'At least one date entry is required.',
                    'dates.array' => 'Dates must be provided in a valid list.',
                    'dates.min' => 'You must select at least one date.',

                    'dates.*.date.required' => 'Each date entry is required.',
                    'dates.*.date.date' => 'Each date must be a valid date format.',

                    'dates.*.timeOfDay.required' => 'The time of day is required for each date.',
                    'dates.*.timeOfDay.string' => 'The time of day must be a valid text value.',

                    'document.mimes' => 'The document must be a PDF file.',
                    'document.max' => 'The document size may not exceed 2MB.',
                ];
            break;
            case 'event':
                return [
                    'title.required' => 'Please provide the event title/purpose.',
                    'title.max' => 'Title must not exceed 255 characters.',

                    'type_id.required' => 'Please select an activity type.',
                    'type_id.exists' => 'The selected activity type is invalid.',

                    'audience_id.required' => 'Please select an audience.',
                    'audience_id.exists' => 'The selected audience is invalid.',

                    'mode_id.required' => 'Please select a mode.',
                    'mode_id.exists' => 'The selected mode is invalid.',
                ];
            break;
            case 'travel':
                return [
                    'events.required' => 'Please tag or create at least one event for this travel.',
                    'events.*.exists' => 'One of the selected events is invalid.',

                    'purpose.required' => 'Please provide the purpose of this travel.',
                    'purpose.string' => 'Purpose must be a valid text.',
                    'purpose.max' => 'Purpose must not exceed 255 characters.',

                    'remarks.string' => 'Remarks must be a valid text.',
                    'remarks.max' => 'Remarks must not exceed 255 characters.',

                    'date.required' => 'Please select a travel date.',
                    'date.string' => 'Date must be a valid format.',

                    'time.required' => 'Please specify time.',
                    'time.string' => 'Time must be a valid format.',

                    'mode_id.required' => 'Please select a mode of travel.',
                    'mode_id.exists' => 'The selected mode of travel is invalid.',

                    'transpo_id.required_if' => 'Please select transpo.',
                    'vehicle.required_if' => 'Please select vehicle.',

                    'expense_id.required' => 'Please select a travel expense type.',
                    'expense_id.exists' => 'The selected expense type is invalid.',

                    'expenses.array' => 'Expenses must be a valid list.',
                    'expenses.required' => 'Please select at least one expense.',

                    'tags.required' => 'Please select at least one employee.',

                    'document.file' => 'The travel document must be a valid file.',
                    'document.mimes' => 'The travel document must be a PDF file.',
                    'document.max' => 'The travel document must not exceed 2MB.',
                ];
            break;
            case 'reservation':
                return [
                    'event_id.required' => 'Please tag or create an event for this reservation.',
                    'event_id.exists' => 'The selected event is invalid.',

                    'purpose.required' => 'Please provide the purpose of this reservation.',
                    'purpose.string' => 'Purpose must be a valid text.',
                    'purpose.max' => 'Purpose must not exceed 255 characters.',

                    'remarks.string' => 'Remarks must be a valid text.',
                    'remarks.max' => 'Remarks must not exceed 255 characters.',

                    'date.required' => 'Please select a travel date.',
                    'date.string' => 'Date must be a valid format.',

                    'time.required' => 'Please specify the departure time.',
                    'time.string' => 'Time must be a valid format.',

                    'vehicle.required' => 'Please select a vehicle.',
                    'vehicle.exists' => 'The selected vehicle is invalid.',

                    'tags.required' => 'Please select at least one employee.',

                    'document.file' => 'The travel document must be a valid file.',
                    'document.mimes' => 'The travel document must be a PDF file.',
                    'document.max' => 'The travel document must not exceed 2MB.',
                ];
            break;
            case 'training':
                return [
                    'purpose.required' => 'Please provide the purpose of travel.',
                    'purpose.string' => 'Purpose must be a valid text.',
                    'purpose.max' => 'Purpose must not exceed 255 characters.',
                    'tags.required' => 'Please select at least one employee.',
                    'document.file' => 'The travel document must be a valid file.',
                    'document.mimes' => 'The travel document must be a PDF file.',
                    'document.max' => 'The travel document must not exceed 2MB.',
                ];
            break;
         }
    }
}
