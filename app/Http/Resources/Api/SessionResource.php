<?php

namespace App\Http\Resources\Api;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'id' => $this->id,
            'key' => $key,
            'code' => $this->code,
            'title' => $this->title,
            'schedules' => $this->schedules,
            'detail' => $this->detail,
            'venue' => $this->venue,
            'activities' => $this->activities,
            'managers' => $this->managers,
            'participants' => $this->participants,
            'status' => $this->status,
            'event' => new EventViewResource($this->event),
            'is_closed' => ($this->is_closed) ? true : false,
            'is_invitational' => ($this->is_invitational) ? true : false,
            'is_exclusive' => ($this->is_exclusive) ? true : false,
            'is_limited' => ($this->is_limited) ? true : false,
            'has_registration' => ($this->has_registration) ? true : false,
            'link' => ($this->has_registration) ? base64_encode($key) : '',
            'has_registered' => $this->has_registered,
            'has_registered' => $this->has_registered,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
