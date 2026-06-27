<?php

namespace App\Http\Resources\Portal\Whereabouts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'mobile' => $this->mobile,
            'birthdate' => $this->birthdate,
            'suffix' => $this->suffix,
            'marital' => $this->marital,
            'religion' => $this->religion,
            'blood' => $this->blood,
            'sex' => $this->sex,
        ];
    }
}
