<?php

namespace App\Http\Resources\HumanResource\Visitor;

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
            'username' => $this->username,
            'name' => $this->name,
            'affiliation' => $this->affiliation,
            'designation' => $this->designation,
            'type' => $this->type,
            'faces' => $this->faces,
            'logs' => $this->logs,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
