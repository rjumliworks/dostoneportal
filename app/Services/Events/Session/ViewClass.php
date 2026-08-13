<?php

namespace App\Services\Events\Session;

use Hashids\Hashids;
use App\Models\EventSession;
use App\Models\EventSessionAttendance;
use App\Http\Resources\Event\Session\ViewResource;

class ViewClass
{
     public function lists($request){
        $data = ViewResource::collection(
            EventSession::with('venue','detail','schedules','attendees.participant','status','activities.speaker','managers.user.profile')
            ->with('participants.participant.detail','feedbackable.participant.detail')
            ->with('event.detail.region:code,name,region','event.detail.province:code,name','event.detail.municipality:code,name','event.detail.barangay:code,name')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->whereHas('event', function($query){
                $query->where('is_active',1);
            })
            ->whereHas('managers', function($query){
                $query->where('user_id',\Auth::user()->id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($id, $todayOnly = false){

        $hashids = new Hashids('krad',10);
        $key = $hashids->decode($id);

        $session = EventSession::with([
                'venue','detail','schedules',
                'participants' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'participants.participant.detail',
                'participants.status',
                'participants.participant.csfs' => function ($q) use ($key) {
                    $q->where('feedbackable_type', EventSession::class)
                    ->where('feedbackable_id', $key[0]);
                },
                'attendees' => function ($q) {
                    $q->orderBy('attended_at', 'DESC');
                },
                'attendees.participant.detail.affiliation',
                'status','activities.speaker',
                'managers.user:id','managers.user.profile:user_id,firstname,lastname,middlename,suffix_id',
                'event.detail.region:code,name,region',
                'event.detail.province:code,name',
                'event.detail.municipality:code,name',
                'event.detail.barangay:code,name',
                'questions.participant.detail'
            ])
            ->where('id',$key[0])->first();

        // The scanning/kiosk page shows a running list of who has checked in
        // "today" — sessions can span multiple days, so that list must come
        // from event_session_attendances (one row per participant per day)
        // instead of the session-wide `attendees` relation, which only knows
        // each participant's most recent check-in.
        if ($todayOnly && $session) {
            $todaysAttendees = EventSessionAttendance::with('participant.detail.affiliation')
                ->where('session_id', $session->id)
                ->whereDate('date', now()->toDateString())
                ->whereNotNull('attended_at')
                ->orderBy('attended_at', 'DESC')
                ->get();
            $session->setRelation('attendees', $todaysAttendees);
        }

        return new ViewResource($session);
    }


}
