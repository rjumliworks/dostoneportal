<?php

namespace App\Http\Resources\Portal\Approval;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user->profile->name,
            'role' => $this->signatory ? $this->signatory->designationable->designation->name : null,
            'oic' => ($this->is_designated) ? '' : 'OIC - ',
        ];
    }
}
