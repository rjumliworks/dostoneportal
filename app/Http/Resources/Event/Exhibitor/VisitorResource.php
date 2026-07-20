<?php

namespace App\Http\Resources\Event\Exhibitor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->participant->name,
            'email' => $this->participant->email,
            'mobile' => $this->participant->mobile,
            'affiliation' => $this->participant->detail->affiliation,
            'designation' => $this->participant->detail->designation,
            'avatar' => $this->participant->detail->avatar,
            'visited_at' => $this->created_at 
        ];
    }
}
