<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        switch($this->option){
            case 'session':
                return [
                    'title' => 'sometimes|required',
                    'venue_id' => 'sometimes|required',
                    'capacity' => 'sometimes|required',
                    'description' => 'sometimes|required',
                    'dates' => 'sometimes|required|array|min:1',
                    'dates.*.date' => 'required|date_format:Y-m-d',
                    'dates.*.timeOfDay' => 'required|in:AM,PM',
                ];
            break;
            default: 
                return [];
        }
    }
}
