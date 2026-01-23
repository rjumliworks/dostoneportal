<?php

namespace App\Http\Resources\Portal\Request;

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
        $key = $hashids->encode($this->request_id);

        switch($this->request->type->name){
            case 'Travel Order':
                $subtype = $this->request->travel->mode->name;
            break;
            case 'Leave Form':
                $subtype = $this->request->leave->type->name;
            break;
            case 'Render Overtime Service':
                $subtype = $this->request->type->name;
            break;
            default:
                $subtype = $this->request->reservation->vehicle->name;
        }

        $link = Str::slug($this->request->type->name) . 'krad' . $key;

        return [
            'id' => $this->id,
            'key' => $key,
            'code' => $this->request->code,
            'type' => $this->request->type->name,
            'status' => $this->status,
            'link' => Crypt::encryptString($link),
            'purpose' => $this->request->detail->purpose,
            'remarks' => $this->request->detail->remarks,
            'start' => $this->request->dates[0]->start,
            'end' => $this->request->dates[0]->end,
            'tags' => TagResource::collection($this->request->tags),
            'created_at' => $this->request->created_at,
            'updated_at' => $this->request->updated_at,
            'subtype' => $subtype
        ];
    }
}
