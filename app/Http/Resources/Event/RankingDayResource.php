<?php

namespace App\Http\Resources\Event;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RankingDayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'date' => $this['date'],
            'label' => Carbon::parse($this['date'])->format('M d, Y'),
            'rankings' => RankingResource::collection($this['rankings']),
        ];
    }
}
