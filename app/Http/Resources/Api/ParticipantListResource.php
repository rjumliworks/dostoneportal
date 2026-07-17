<?php

namespace App\Http\Resources\Api;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($this->id);

        return [
            'code' => $code,
            'participant' => new ParticipantSoloResource($this->participant),
            'session_id' => $this->session_id,
            'participant_id' => $this->participant_id,
            'status' => $this->status,
            'attended_at' => $this->attended_at,
            'created_at' => $this->created_at
        ];
    }
}
