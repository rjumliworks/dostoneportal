<?php

namespace App\Http\Resources\Event;

use Illuminate\Support\Facades\Crypt;
use Hashids\Hashids;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);
        
        $encryptedKey = Crypt::encryptString($key);
        $reg_link = config('app.registration_url') . '/registration/' . $encryptedKey;
        $attendance_link = config('app.registration_url') . '/session/' . $key;

        return [
            'id' => $this->id,
            'registration' => $reg_link,
            'attendance' => $attendance_link,
            'reference' => $this->reference,
            'code' => $this->code,
            'title' => $this->title,
            'schedules' => $this->schedules,
            'detail' => $this->detail,
            'venue' => $this->venue,
            'activities' => $this->activities,
            'managers' => $this->managers,
            'participants' => $this->participants,
            'attendees' => $this->attendees,
            'status' => $this->status,
            'event' => new IndexResource($this->event),
            'is_closed' => ($this->is_closed) ? true : false,
            'is_invitational' => ($this->is_invitational) ? true : false,
            'is_exclusive' => ($this->is_exclusive) ? true : false,
            'is_limited' => ($this->is_limited) ? true : false,
            'has_registration' => ($this->has_registration) ? true : false,
            'link' => ($this->has_registration) ? base64_encode($this->reference) : '',
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
