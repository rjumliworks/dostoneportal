<?php

namespace App\Services\Events\Participant;

use App\Models\Participant;
use App\Models\ParticipantDetail;
use App\Models\ListData;
use App\Http\Resources\Event\Participant\ListResource;
use App\Http\Resources\Event\Participant\ShowResource;

class ViewClass
{
    public function counts(){
        $types = ListData::where('is_active',1)->where('type','Participant')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'count' => ParticipantDetail::where('type_id',$item->id)->count()
            ];
        });
        return $types;
    }

    public function list($request){
        $query = Participant::with(['detail.type','detail.affiliation'])->withCount('sessions');

        // firstname/lastname/email are encrypted at rest (see Participant's set*Attribute
        // mutators), so they can't be matched with a SQL LIKE. All records are returned
        // unpaginated so the frontend can filter by the decrypted name client-side instead.
        $query->orderBy('created_at', 'DESC');

        $data = ListResource::collection($query->get());
        return $data;
    }

    public function show($id){
        $participant = Participant::with([
            'detail.type',
            'detail.affiliation',
            'sessions.session.venue',
            'sessions.session.schedules',
            'sessions.status',
        ])->findOrFail($id);

        return new ShowResource($participant);
    }
}
