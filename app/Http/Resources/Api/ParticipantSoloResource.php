<?php

namespace App\Http\Resources\Api;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantSoloResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);

        return [
            'id' => $key,
            'code' => $this->code,
            'email' => $this->email,
            'contact_no' => $this->contact_no,
            'name' => $this->firstname.' '.$this->lastname,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'suffix' => $this->suffix,
            'avatar' => ($this->detail->avatar === 'avatar.jpg') ? '/images/avatars/'.$this->detail->avatar : '/storage/images/avatars/'.$this->detail->avatar,
            'designation' => $this->detail->designation,
            'affiliation' => $this->detail->affiliation,
            'birthdate' => $this->detail->birthdate,
            'type' => $this->detail->type,
            'sex' => $this->detail->sex,
            'has_csf' => $this->has_csf,
            'is_completed' => $this->is_completed
        ];
    }
}
