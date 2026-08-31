<?php

namespace App\Http\Resources\Trace\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [
                'name' => null,
                'address' => null,
                'region' => null,
                'province' => null,
                'municipality' => null,
                'barangay' => null,
                'latitude' => null,
                'longitude' => null,
            ];
        }

        $address = $this->address ? $this->address.', ' : '';
        $barangayName = optional($this->barangay)->name;
        $municipalityName = optional($this->municipality)->name;

        return [
            'name' => $address.$barangayName.', '.$municipalityName,
            'address' => $this->address,
            'region' => $this->region,
            'province' => $this->province,
            'municipality' => $this->municipality,
            'barangay' => $this->barangay,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
