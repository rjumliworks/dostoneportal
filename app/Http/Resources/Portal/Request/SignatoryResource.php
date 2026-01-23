<?php

namespace App\Http\Resources\Portal\Request;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignatoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'division' => $this->division,
            'statuses' => StatusResource::collection($this->statusable),
            'recommended' => $this->recommended ? ($this->recommended->user->profile->name) : null,
            'recommended_role' => $this->recommended ? $this->recommended->signatory->designationable->designation->name : null,
            'recommended_oic' => $this->recommended ? ($this->recommended->is_designated) ? '' : '(OIC)' : null,
            'recommended_date' => $this->recommended ? $this->recommended_date : null,
            'recommended_by' => asset('storage/'.$this->recommended_by),
            'approved' => $this->approved ? ($this->approved->user->profile->name) : null,
            'approved_date' => $this->approved ? $this->approved_date : null,
            'approved_by' => $this->approved ? asset('storage/'.$this->approved_by) : null,
            'approved_oic' => $this->approved ? ($this->approved->is_designated) ? '' : '(OIC)' : null,
            'approved_role' => $this->approved ? $this->approved->signatory->designationable->designation->name : null,
            'is_disapproved' => $this->is_disapproved,
            'is_approval_only' => $this->is_approval_only
        ];
    }
}
