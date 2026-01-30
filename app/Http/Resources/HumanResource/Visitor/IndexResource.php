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
            'avatar' => ($this->avatar !== 'noavatar.jpg')
            ? asset('storage/' . $this->avatar) 
            : asset('images/avatars/avatar.jpg'), 
            'username' => $this->username,
            'name' => $this->name,
            'affiliation' => $this->affiliation,
            'designation' => $this->designation,
            'type' => $this->type,
            'faces' => $this->faces,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
