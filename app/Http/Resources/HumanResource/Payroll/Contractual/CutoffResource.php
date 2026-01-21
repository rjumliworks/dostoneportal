<?php

namespace App\Http\Resources\HumanResource\Payroll\Contractual;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CutoffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($this->id);

        return [
            'id' => $this->id,
            'code' => $code,
            'unique' => $this->code,
            'start' => $this->start,
            'end' => $this->end,
            'type' => $this->type,
            'is_locked' => $this->is_locked,
            'cycle' => $this->cycle,
            'status' => $this->status,
            'total' => $this->total,
            'count' => $this->count,
            'user' => $this->user,
            'batch' => $this->batch,
            'payrolls' => PayrollResource::collection($this->payrolls),
            'created_at' => $this->created_at
        ];
    }
}
