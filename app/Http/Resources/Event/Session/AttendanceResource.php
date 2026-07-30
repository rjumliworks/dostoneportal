<?php

namespace App\Http\Resources\Event\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $detail = $this->participant->detail;

        return [
            'name' => $this->participant->name,
            'avatar' => $detail?->avatar,
            'affiliation' => $detail?->affiliation?->name === 'Others'
                ? $detail->others
                : $detail?->affiliation?->name,
            'datetime' => $this->attended_at,
        ];
    }
}
