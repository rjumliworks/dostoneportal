<?php

namespace App\Http\Resources\Portal\Request;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->request->id);

        $userDivisionId = \Auth::user()->organization->division_id ?? null;

   


        return [
            'id' => $this->id,
            'request_key' => $key,
            'request_id' => $this->request->id,
            'code' => $this->request->code,
            'count' => $this->count,
            'nopay' => $this->nopay,
            'pay' => $this->pay,
            'leave' => $this->type,
            'detail' => $this->detail->name,
            'type' => $this->request->type->name,
            'credits' => $this->credits,
            'purpose' => $this->request->detail->purpose,
            'remarks' => $this->request->detail->remarks,
            'start' => $this->request->dates[0]->start,
            'end' => $this->request->dates[0]->end,
            'time' => $this->request->dates[0]->time,
            'signatory' => new SignatoryResource($this->request->signatories[0]),
            'statuses' => StatusResource::collection($this->request->signatories[0]->statusable),
            'employee' => $this->request->user->profile->firstname.' '.$this->request->user->profile->lastname,
            'comments' => CommentResource::collection($this->request->comments),
            'signatories' => count($this->request->signatories) === 1 
                    ? new SignatoryResource($this->request->signatories[0]) 
                    : SignatoryResource::collection($this->request->signatories),
            'tags' => TagResource::collection($this->request->tags),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
