<?php

namespace App\Http\Resources\HumanResource\Survey;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($this->id);

        return [
            'id' => $this->id,
            'code' => $code,
            'year' => $this->year,
            'semester' => $this->semester,
            'is_active' => $this->is_active,
            'answers_count' => $this->answers_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'finished_at' => $this->finished_at,
        ];
    }
}
