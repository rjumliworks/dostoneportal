<?php

namespace App\Services\Trace\Event;

use Hashids\Hashids;
use App\Models\User;
use App\Models\Request;
use App\Models\RequestEvent;
use App\Http\Resources\Trace\Event\IndexResource;

class ViewClass
{
    public function groupCount($request){
        $data = Request::findOrFail($request->id);

        $existingUserIds = $data->tags()->pluck('user_id')->toArray();

        $count = User::where('is_active', 1)
            ->whereHas('organization', function ($org) use ($request) {
                if ($request->type === 'group') {
                    $org->when($request->division_id, fn($q) => $q->where('division_id', $request->division_id))
                        ->when($request->unit_id, fn($q) => $q->where('unit_id', $request->unit_id));
                }
            })
            ->whereNotIn('id', $existingUserIds)
            ->count();

        return [
            'count' => $count
        ];
    }

    public function counts($types){
        $user_id = \Auth::user()->id;
        foreach($types as $type){
            $counts[] = RequestEvent::whereHas('request.tags', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('mode_id',$type['value'])->count();
        }
        return $counts;
    }

    public function lists($request){
        // $user_id = \Auth::user()->id;
        // $division_id = \Auth::user()->organization->division_id;
        $data = RequestEvent::with([
            'mode','types','audience',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id','request.tags.status',
            'request.tags.user.organization.division','request.tags.user.organization.unit','request.tags.user.organization.position',
            'request.dates',
            'request.location',
            'request.detail',
        ])
        ->when($request->audience, fn($q, $audience) => $q->where('audience_id', $audience))
        ->when($request->mode, fn($q, $mode) => $q->where('mode_id', $mode))
        ->when($request->type, fn($q, $type) => $q->whereHas('types', fn($q2) => $q2->where('list_events.id', $type)))
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('request.user.profile', function ($q) use ($keyword) {
                $q->whereRaw('LOWER(lastname) LIKE ?', ['%' . strtolower($keyword) . '%']);
            });
        })
        // ->whereHas('request.tags', function ($query) use ($user_id) {
        //     $query->where('user_id', $user_id);
        // })
        // ->whereIn('division_id',[$division_id,48])
        ->latest() 
        ->paginate($request->count ?? 10);

        return IndexResource::collection($data);
    }

    public function event($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);
      
        $data = RequestEvent::with([
            'mode','types','audience',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.dates',
            'request.location',
            'request.detail',
        ])
        ->where('id',$id)
        ->first();

        return new IndexResource($data);
    }

    public function search($request){
        $keyword = $request->keyword;
    
        $start = \Carbon\Carbon::parse($request->start)->startOfDay();
        $end = \Carbon\Carbon::parse($request->end)->endOfDay();
        
        $data =  User::with([
            'profile',
            'organization.position',
            'organization.division',
            'organization.type'
        ])
        ->when($keyword, function ($query) use ($keyword){
            $query->whereHas('profile', function ($q) use ($keyword) {
                $q->where('lastname', 'like', '%' . $keyword . '%');
            });
        })
        ->limit(5)->get()->map(function ($item) use ($start, $end){
            $conflicts = $item->tags()
                ->whereHas('request.dates', function ($q) use ($start, $end) {
                    $q->where('start', '<=', $end)
                      ->where('end', '>=', $start);
                })
                ->with([
                    'request.type',
                    'request.dates' => function ($q) {
                        $q->select('id', 'request_id', 'start', 'end');
                    }
                ])
                ->get()->map(function ($con){
                    return [
                        'type' => $con->request->type->name,
                        'dates' => $con->request->dates
                    ];
                });

            $isAvailable = $conflicts->isEmpty();
            return [
                'value' => $item->id,
                'name' => $item->profile->name,
                'position' => optional($item->organization->position)->name,
                'division' => optional($item->organization->division)->name,
                'division_id' => optional($item->organization->division)->id,
                'type' => $item->organization->type->name,
                'avatar' => $item->profile?->avatar,
                'available' => $isAvailable,
                'conflicts' => $conflicts 
            ];
        });
        return $data;
    }
}
