<?php

namespace App\Http\Resources\Portal\Request;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);

        $userDivisionId = \Auth::user()->organization->division_id ?? null;

        $divisionSignatory = $this->signatories()
        ->where('division_id', $userDivisionId)
        ->first();

        return [
            'id' => $this->id,
            'request_key' => $key,
            'request_id' => $this->id,
            'code' => $this->code,
            'type' => $this->type->name,
            'purpose' => $this->detail->purpose,
            'remarks' => $this->detail->remarks,
            'start' => $this->dates[0]->start,
            'end' => $this->dates[0]->end,
            'time' => $this->dates[0]->time,
            'signatory' => new SignatoryResource($divisionSignatory),
            'statuses' => StatusResource::collection($divisionSignatory?->statusable),
            'signatories' => SignatoryResource::collection($this->signatories),
            'employee' => $this->user->profile->fullname,
            'tags' => TagResource::collection($this->tags),
            'comments' => CommentResource::collection($this->comments),
            'overtime' => $this->overtime,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
