<?php

namespace App\Http\Resources\HumanResource\Credit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
       return parent::toArray($request);
    }
}
