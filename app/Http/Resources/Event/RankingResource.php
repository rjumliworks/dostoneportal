<?php

namespace App\Http\Resources\Event;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RankingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $participant = $this->point?->participant;

        return [
            'participant_id' => $participant?->id,
            'code' => $participant?->code,
            'name' => $participant?->name,
            'avatar' => $participant?->detail?->avatar,
            'affiliation' => $participant?->detail?->affiliation,
            'points' => (int) $this->total_points,
            'last_earned_at' => $this->last_earned_at ? Carbon::parse($this->last_earned_at)->format('M d, Y g:i a') : null,
        ];
    }
}
