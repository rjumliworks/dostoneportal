<?php

namespace App\Http\Resources\Trace\Event;

use Hashids\Hashids;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);
        $request_key = $hashids->encode($this->request_id);
        
        $link = Str::slug($request_key) . 'krad' . $key;

        return [
            'id' => $this->id,
            'key' => $key,
            'request_key' => $request_key,
            'request_id' => $this->request->id,
            'code' => $this->request->code,
            'title' => $this->title,
            'link' => Crypt::encryptString($link),
            'purpose' => $this->request->detail->purpose,
            'remarks' => $this->request->detail->remarks,
            'start' => $this->request->dates[0]->start,
            'end' => $this->request->dates[0]->end,
            'time' => $this->request->dates[0]->time,
            'type' => $this->type,
            'audience' => $this->audience,
            'mode' => $this->mode,
            'tags' => TagResource::collection($this->request->tags),
            'location' => new LocationResource($this->request->location),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
