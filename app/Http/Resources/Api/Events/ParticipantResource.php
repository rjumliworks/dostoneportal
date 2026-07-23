<?php

namespace App\Http\Resources\Api\Events;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);

        $result = (new Builder(
            writer: new PngWriter(),
            data: $this->code,
            size: 500,
            margin: 5,
            logoPath: public_path('images/qrlogo.png'),
            logoResizeToWidth: 80
        ))->build();

        $qr = 'data:image/png;base64,' . base64_encode($result->getString());

        return [
            'id' => $this->id,
            'qr' => $qr,
            'code' => $this->code,
            'email' => $this->email,
            'contact_no' => $this->mobile,
            'name' => $this->firstname.' '.$this->lastname,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'suffix' => $this->suffix,
            'avatar' => $this->detail->avatar,
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
