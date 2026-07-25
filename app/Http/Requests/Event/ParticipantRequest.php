<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id' => 'sometimes|required|integer',
            'email' => 'sometimes|required|email|max:150|unique:participants,kradworkz,'.$this->id,
            'firstname' => 'sometimes|required|string|max:100',
            'lastname' => 'sometimes|required|string|max:100',
            'middlename' => 'sometimes|required|string|max:50',
            'suffix' => 'sometimes|nullable|string|max:10',
            'sex_id' => 'sometimes|required|integer',
            'birthdate' => 'sometimes|required',
            'affiliation' =>'sometimes|required',
            'designation' =>'sometimes|required',
            'mobile' => [
                'sometimes',
                'required',
                'numeric',
                'digits:11',
                function ($attribute, $value, $fail) {
                    $hash = hash('sha256', $value);

                    $exists = \App\Models\Participant::where('mobile_hash', $hash)
                        ->when($this->id, fn ($q) => $q->where('id', '!=', $this->id)) // ignore current record on update
                        ->exists();

                    if ($exists) {
                        $fail('The contact number has already been taken.');
                    }
                },
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $hash = hash('sha256', $value);

                    $exists = \App\Models\Participant::where('kradworkz', $hash)
                        ->when($this->id, fn ($q) => $q->where('id', '!=', $this->id)) // ignore current record on update
                        ->exists();

                    if ($exists) {
                        $fail('The email has already been taken.');
                    }
                },
            ],
            'signature' => 'sometimes|required|image|mimes:png,jpg,jpeg|max:2048',
            'avatar' => 'sometimes|required|image|mimes:png,jpg,jpeg|max:2048'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            if (empty($data['firstname']) || empty($data['middlename']) || empty($data['lastname'])) {
                $validator->errors()->add('fullname', 'Full name (first, middle, and last) is required.');
            }
        });
    }
}
