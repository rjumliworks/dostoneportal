<?php

namespace App\Services\Events;

use Hashids\Hashids;
use App\Models\Event;
use App\Models\ParticipantPointLog;
use App\Http\Resources\Event\ViewResource;
use App\Http\Resources\Event\IndexResource;

class ViewClass
{
     public function lists($request){
        $data = IndexResource::collection(
            Event::with('venues')
            ->with('exhibitors.contact')
            ->with('sessions.venue','sessions.detail','sessions.schedules','sessions.status','sessions.participants','sessions.attendees','sessions.managers.user.profile')
            ->with('detail.region:code,name,region','detail.province:code,name','detail.municipality:code,name','detail.barangay:code,name')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($id){
        $hashids = new Hashids('krad',10);
        $key = $hashids->decode($id);

        $event = Event::with('venues')
            ->with([
                'exhibitors' => function ($query) {
                    $query->with('contact')
                        ->withCount([
                            'visitors',
                            'visitors as voted_count' => function ($q) {
                                $q->where('has_voted', 1);
                            }
                        ]);
                }
            ])
            ->with('sessions.venue','sessions.detail','sessions.schedules','sessions.managers.user.profile')
            ->with('detail.region:code,name,region','detail.province:code,name','detail.municipality:code,name','detail.barangay:code,name')
            ->where('id',$key[0])->first();

        if ($event) {
            $event->rankings = $this->rankings();
        }

        return new ViewResource($event);
    }

    private function rankings()
    {
        $dates = ParticipantPointLog::selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date');

        return $dates->map(function ($date) {
            return [
                'date' => $date,
                'rankings' => ParticipantPointLog::whereDate('created_at', $date)
                    ->selectRaw('point_id, SUM(points) as total_points, MAX(created_at) as last_earned_at')
                    ->groupBy('point_id')
                    ->orderByDesc('total_points')
                    ->with('point.participant.detail.affiliation')
                    ->get(),
            ];
        });
    }
}
