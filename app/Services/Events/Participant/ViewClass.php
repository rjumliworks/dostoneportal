<?php

namespace App\Services\Events\Participant;

use App\Models\Participant;
use App\Models\ParticipantDetail;
use App\Models\ListData;
use App\Http\Resources\Event\Participant\ListResource;

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
        // mutators), so they can't be matched with a SQL LIKE - keyword search is limited
        // to the plaintext participant code instead.
        $query->when($request->keyword, function ($query, $keyword) {
            $query->where('code', 'like', "%{$keyword}%");
        });

        $query->when($request->type, function ($query, $type) {
            $query->whereHas('detail', fn($q) => $q->where('type_id', $type));
        });

        $query->when($request->registration, function ($query, $registration) {
            if ($registration === 'regular') {
                $query->doesntHave('sessions');
            } elseif ($registration === 'session') {
                $query->has('sessions');
            }
        });

        $query->orderBy('created_at', 'DESC');

        $data = ListResource::collection($query->paginate($request->count));
        return $data;
    }
}
