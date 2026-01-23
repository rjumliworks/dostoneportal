<?php

namespace App\Http\Resources\Portal\Approval;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);
        $request_key = $hashids->encode($this->request_id);

        return [
            'id' => $this->id,
            'key' => $key,
            'request_key' => $request_key,
            'request_id' => $this->request->id,
            'type' => $this->request->type->name,
            'purpose' => $this->request->detail->purpose,
            'remarks' => $this->request->detail->remarks,
            'start' => $this->request->dates[0]->start,
            'end' => $this->request->dates[0]->end,
            'time' => $this->request->dates[0]->time,
            'status' => $this->status,
            'employee' => $this->request->user->profile->firstname.' '.$this->request->user->profile->lastname,
            'tags' => TagResource::collection($this->request->tags),
            'comments' => CommentResource::collection($this->request->comments),
            'vehicle' => $this->request->reservation->vehicle,
            'location' => new LocationResource($this->request->location),
            'approved' => new SigResource($this->approved),
            'approved_date' => $this->approved_date,
            'approved_by' => $this->approved_by,
            'recommended' => new SigResource($this->recommended),
            'recommended_date' => $this->recommended_date,
            'recommended_by' => $this->recommended_by,
            'division' => $this->division,
            'is_disapproved' => $this->is_disapproved,
            'is_approval_only' => $this->is_approval_only,
            'statuses' => StatusResource::collection($this->statusable),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
