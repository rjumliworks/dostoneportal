<?php

namespace App\Http\Resources\Event\Session;

use Illuminate\Support\Facades\Crypt;
use Hashids\Hashids;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use App\Http\Resources\Event\IndexResource as EventResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);
        $randomkey = Str::random(10);
        
        $url = $_SERVER['HTTP_HOST'].'/verification/'.$key;
        $result = (new Builder(
            writer: new PngWriter(),
            data: $url,
            size: 500,
            margin: 5,
            logoPath: public_path('images/qrlogo.png'),
            logoResizeToWidth: 80
        ))->build();

        $qr = 'data:image/png;base64,' . base64_encode($result->getString());


        $encryptedKey = Crypt::encryptString($key);
        $reg_link = url("registration/{$encryptedKey}");
        $result1 = (new Builder(
            writer: new PngWriter(),
            data: $reg_link,
            size: 500,
            margin: 5,
            logoPath: public_path('images/qrlogo.png'),
            logoResizeToWidth: 80
        ))->build();
        $reg_qr = 'data:image/png;base64,' . base64_encode($result1->getString());

        return [
            'qr' => $qr,
            'reg_link' => $reg_link,
            'reg_qr' => $reg_qr,
            'id' => $this->id,
            'key' => $key,
            'randomkey' => $randomkey,
            'event_id' => $this->event->reference,
            'code' => $this->code,
            'title' => $this->title,
            'schedules' => $this->schedules,
            'detail' => $this->detail,
            'venue' => $this->venue,
            'activities' => $this->activities,
            'managers' => ManagerResource::collection($this->managers),
            'participants' => ParticipantListResource::collection($this->participants),
            'attendees' => AttendanceResource::collection($this->attendees),
            'status' => $this->status,
            'questions' => QuestionResource::collection($this->questions),
            'event' => new EventResource($this->event),
            'is_closed' => ($this->is_closed) ? true : false,
            'is_invitational' => ($this->is_invitational) ? true : false,
            'is_exclusive' => ($this->is_exclusive) ? true : false,
            'is_limited' => ($this->is_limited) ? true : false,
            'has_registration' => ($this->has_registration) ? true : false,
            'link' => ($this->has_registration) ? base64_encode($key) : '',
            'feedbacks' => FeedbackResource::collection($this->feedbackable),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
