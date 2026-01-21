<?php

namespace App\Http\Resources\HumanResource\Dtr;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => date('F d, Y', strtotime($this->date)),
            'user' => $this->user,
            'name' => $this->user->profile->name,
            'am_in_at' => new TimeResource(json_decode($this->am_in_at)),
            'am_out_at' => new TimeResource(json_decode($this->am_out_at)),
            'pm_in_at' => new TimeResource(json_decode($this->pm_in_at)),
            'pm_out_at' => new TimeResource(json_decode($this->pm_out_at)),
            'remarks' => json_decode($this->remarks),
            'tardiness' => $this->tardiness,
            'undertime' => $this->undertime,
            'is_updated' => $this->is_updated,
            'is_completed' => $this->is_completed,
            'is_updated' => $this->is_updated,
            'created_at' => $this->created_at
        ];
    }
}
