<?php

namespace App\Http\Resources\Portal\Request;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);
        $request_key = $hashids->encode($this->request_id);

        $userDivisionId = \Auth::user()->organization->division_id ?? null;

        $divisionSignatory = $this->request->signatories()
        ->where('division_id', $userDivisionId)
        ->first();

        return [
            'id' => $this->id,
            'key' => $key,
            'request_key' => $request_key,
            'request_id' => $this->request->id,
            'code' => $this->request->code,
            'type' => $this->request->type->name,
            'documents' => $this->request->documents,
            'purpose' => $this->request->detail->purpose,
            'remarks' => $this->request->detail->remarks,
            'start' => $this->request->dates[0]->start,
            'end' => $this->request->dates[0]->end,
            'time' => $this->request->dates[0]->time,
            'signatory' => new SignatoryResource($divisionSignatory),
            'statuses' => StatusResource::collection($divisionSignatory?->statusable),
            'signatories' => SignatoryResource::collection($this->request->signatories),
            'employee' => $this->request->user->profile->firstname.' '.$this->request->user->profile->lastname,
            'mode' => $this->mode,
            'events' => $this->events->map(function ($event) {
                $firstDate = $event->request?->dates?->first();
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'types' => $event->types,
                    'audience' => $event->audience,
                    'mode' => $event->mode,
                    'is_host' => $event->is_host,
                    'location' => $event->request?->location ? new LocationResource($event->request->location) : null,
                    'start' => $firstDate?->start,
                    'end' => $firstDate?->end,
                    'time' => $firstDate?->time,
                ];
            }),
            'tags' => TagResource::collection($this->request->tags),
            'expense' => $this->expense,
            'expenses' => $this->expense_items,
            'comments' => CommentResource::collection($this->request->comments),
            'location' => $this->request->location ? new LocationResource($this->request->location) : null,
            'destination' => $this->events->first()?->request?->location ? new LocationResource($this->events->first()->request->location) : null,
            'event_date' => $this->events->first()?->request?->dates?->first() ? [
                'start' => $this->events->first()->request->dates->first()->start,
                'end' => $this->events->first()->request->dates->first()->end,
                'time' => $this->events->first()->request->dates->first()->time,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
