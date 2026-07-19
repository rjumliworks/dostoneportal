<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
// use App\Models\Hotel;
use App\Models\EventCsfQuestion;
use App\Models\EventSession;
use App\Models\EventExhibitor;
use App\Models\ParticipantPoint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\Api\Events\Session\IndexResource as SessionResource;
use App\Http\Resources\Api\Events\Exhibitor\IndexResource as ExhibitorResource;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->user_id;
        return response()->json([
            'event'       => $this->event(),
            'points'      => $this->points($id),
            'sessions'    => $this->sessions($id),
            'exhibitors'  => $this->exhibitors($id),
            'csfs'        => $this->csfs(),
            'hotels'      => $this->hotels()
        ]);
    }

    public function event(){
        $data = Event::where('is_active',1)->value('name');
        return $data;
    }

    public function points($id){
        $point = ParticipantPoint::where('participant_id',$id)->first();
        if(!$point) {
            $point = ParticipantPoint::firstOrCreate(
                [
                    'participant_id' => $id,
                    'points'   => 0
                ]
            );
        }
        $data = ParticipantPoint::where('participant_id',$id)->value('points');
        return $data;
    }

    public function sessions($id)
    {
        $data = EventSession::with('venue','detail','schedules','status','activities.speaker','managers.user.profile')
        ->with('event.detail.region:code,name,region','event.detail.province:code,name','event.detail.municipality:code,name','event.detail.barangay:code,name')
        ->with([
            'questions' => function ($query) {
                $query->latest('created_at')->take(10);
            },
            'feedbackable.participant.detail' => function ($query) {
                $query->latest('created_at')->take(10);
            },
        ])
        ->whereHas('event',function ($query) {
            $query->where('is_active',1);
        })
        ->withExists([
            'participants as has_registered' => fn($q) => 
                $q->where('participant_id', $id),
            'participants as has_attended' => fn($q) => 
                $q->where('participant_id', $id)->whereNotNull('attended_at'),
            'feedbackable as has_feedback' => fn($q) =>
                $q->where('participant_id', $id),
        ])
        ->get();
        return SessionResource::collection($data);
    }

    public function csfs(){
        $data = EventCsfQuestion::where('is_active', 1)->where('is_rating', 1)->get();
        return DefaultResource::collection($data);
    }

    public function exhibitors($id)
    {
        $data = EventExhibitor::with('contact')
        ->whereHas('event', fn($q) => $q->where('is_active', 1))
        ->with([
            'feedbackable.participant.detail' => function ($query) {
                $query->latest('created_at')->take(10);
            },
        ])
        ->withExists([
            'visitors as has_visited' => fn($q) => 
                $q->where('participant_id', $id),
            'visitors as has_voted' => fn($q) =>
                $q->where('participant_id', $id)->where('has_voted', 1),
            'feedbackable as has_feedback' => fn($q) =>
                $q->where('participant_id', $id),
        ])
        ->get();
        return ExhibitorResource::collection($data);
    }

    public function hotels(){
        return [];
        // $data = Hotel::with('location','rates')->where('is_active',1)->get();
        // return HotelResource::collection($data);
    }
}
