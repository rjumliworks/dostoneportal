<?php

namespace App\Http\Controllers\Api;

use App\Models\Dropdown;
use Illuminate\Support\Facades\DB;
use App\Models\ParticipantPoint;
use App\Models\Participant;
use App\Models\EventExhibitor;
use App\Models\EventExhibitorVisitor;
use App\Models\EventSession;
use App\Models\EventSessionQuestion;
use App\Models\EventSessionParticipant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\Api\AttendanceResource;
use App\Http\Resources\Api\SessionResource;
use App\Http\Resources\Api\SessionViewResource;
use App\Http\Resources\Api\Data\QuestionResource;
use App\Http\Resources\ParticipantListResource;
use Illuminate\Support\Facades\Crypt;
use App\Events\SessionEvent;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $participantId = $request->id;

        $data = EventSession::with('venue','detail','schedules','participants','status','activities.speaker','managers.user.profile')
            ->with('event.detail.region:code,name,region','event.detail.province:code,name','event.detail.municipality:code,name','event.detail.barangay:code,name')
            ->whereHas('event',function ($query) {
                $query->where('is_active',1);
            })
            ->with(['participants' => function ($query) use ($participantId) {
                $query->where('participant_id', $participantId);
            }])
            ->get()
            ->map(function ($session) use ($participantId) {
                $session->has_registered = $session->participants->isNotEmpty();
            return $session;
        });
        return SessionResource::collection($data);
    }

    public function view(Request $request, $id){
        $participantId = $request->participant_id;
        $data = EventSession::with('venue','detail','schedules','participants','questions','status','activities.speaker','managers.user.profile','feedbackable.participant.detail')
            ->with('event.detail.region:code,name,region','event.detail.province:code,name','event.detail.municipality:code,name','event.detail.barangay:code,name')
            ->where('id',$id)
            ->first();
            
            if($data){
                $data->has_registered = $data->participants()
                    ->where('participant_id', $participantId)
                    ->exists();
                $data->feedback = $data->feedbackable
                    ->where('participant_id', $participantId)
                    ->first(); 
                $data->feedbacks = $data->feedbackable;
            }
        return new SessionViewResource($data);
    }

    public function attendance(Request $request)
    {   
        $sessionCode = $request->session;
        $participant = Participant::select('id','firstname','lastname')
                    ->where('id', $request->participant_id)
                    ->first();
        try {
            $aa = Crypt::decrypt($sessionCode); // only works if it was encrypted cleanly
            $ex = EventExhibitor::where('code', $aa)->first();
        } catch (\Exception $e) {
            $ex = null;
        }
 
        if($ex){
            $x = EventExhibitorVisitor::where('exhibitor_id',$ex->id)->where('participant_id',$request->participant_id)->first();
            if($x){
                $data = [
                    'participant_id' => $request->participant_id,
                    'name' => $participant->firstname.' '.$participant->lastname,
                    'type' => 'not',
                    'message' => 'You already visited the exhibitor'
                ];
                broadcast(new SessionEvent($data,'exhibit_visit'));
                return response()->json([
                    'status' => false,
                    'message' => 'You are not a registered participant.'
                ], 400);
            }else{
                $new = new EventExhibitorVisitor;
                $new->participant_id = $request->participant_id;
                $new->exhibitor_id = $ex->id;

                if($new->save()){
                    $engage = Dropdown::findOrFail(26);
                    $point = ParticipantPoint::where('participant_id', $request->participant_id)->firstOrFail();

                    $new->update([
                    'has_voted' => true,
                    'voted_at'  => now(),
                    ]);

                    $new->engageable()->create([
                        'points'   => $engage->others,
                        'type_id'  => $engage->id,
                        'point_id' => $point->id,
                    ]);

                    $point->points += $engage->others;
                    $point->save();
                    $data = [
                        'participant_id'        => $request->participant_id,
                        'points'    => $engage->others
                    ];
                    broadcast(new SessionEvent($data, 'plus'));
                }
                $data = [
                    'participant_id' => $request->participant_id,
                    'name' => $participant->firstname.' '.$participant->lastname,
                    'type' => 'not',
                    'message' => 'Thank you for visiting '.$ex->title
                ];
                broadcast(new SessionEvent($data,'exhibit_visit'));
                return response()->json([
                    'status' => true,
                    'message' => 'Thanks.'
                ], 200);
            }
        }else{
            $randomkey = substr($request->session, -10);
            $cipher = substr($request->session, 0, -10);
            $code = Crypt::decrypt($cipher);
            $session_id = EventSession::where('code', $code)->value('id');

            if (!$session_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Session not found.'
                ], 404);
            }

            $attendance = EventSessionParticipant::where('participant_id', $request->participant_id)
                ->where('session_id', $session_id)
                ->first();

            if (!$attendance) {
                $participant = Participant::select('id','firstname','lastname')->where('id',$request->participant_id)->first();
                $data = [
                    'participant_id' => $request->participant_id,
                    'name' => $participant->firstname.' '.$participant->lastname,
                    'type' => 'not',
                    'message' => 'You are not registered as a participant. Please go to the Sessions tab to complete your registration'
                ];
                broadcast(new SessionEvent($data,'attendance-error',$randomkey));
                return response()->json([
                    'status' => false,
                    'message' => 'You are not a registered participant.'
                ], 400);
            }

            if ($attendance->attended_at) {
                if(!$request->image){
                    $participant = Participant::select('id','firstname','lastname')->where('id',$request->participant_id)->first();
                    $data = [
                        'participant_id' => $request->participant_id,
                        'name' => $participant->firstname.' '.$participant->lastname,
                        'type' => 'already',
                        'message' => 'Your attendance has already been recorded'
                    ];
                    broadcast(new SessionEvent($data,'attendance-error',$randomkey));
                    return response()->json([
                        'status' => false,
                        'message' => 'Attendance already recorded for this participant.'
                    ], 400);
                }
            }

            if($request->image){
                $path = $this->image($request);
                $attendance->image = $path;
            }
            if(!$attendance->attended_at){
                $attendance->attended_at = now();
                $attendance->status_id = 8;
            }

            if ($attendance->save()) {
                $latest = EventSessionParticipant::with('participant.detail','session')->where('session_id', $session_id)
                ->where('id', $attendance->id)
                ->first();
                broadcast(new SessionEvent(new AttendanceResource($latest),'attendance',$randomkey));
                return response()->json([
                    'status' => true,
                    'message' => 'Attendance successfully recorded.',
                    'data' => new AttendanceResource($latest)
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to record attendance. Please try again.'
            ], 500);
        }
    }

    public function question(Request $request){
        $data = EventSessionQuestion::create([
            'question' => $request->question,
            'participant_id' => $request->participant_id,
            'session_id' => $request->session_id,
        ]);

        $data = EventSessionQuestion::with('participant.detail')->where('id',$data->id)->first();
        broadcast(new SessionEvent(new QuestionResource($data),'question'));
        return response()->json([
            'status' => true,
            'message' => 'Question submitted successfully',
            'data' => new QuestionResource($data)
        ], 200);
    }

    public function registration(Request $request){
        $data = EventSessionParticipant::create([
            'status_id' => 7,
            'participant_id' => $request->participant_id,
            'session_id' => $request->session_id,
        ]);
        $data = EventSessionParticipant::with('participant.detail')->where('id',$data->id)->first();
        broadcast(new SessionEvent(new ParticipantListResource($data),'register'));
        return response()->json([
            'status' => true,
            'message' => 'Registration submitted successfully',
            'data' => true
        ], 200);
    }

    public function cancel(Request $request){
        $data = EventSessionParticipant::with('participant.detail')->where('participant_id',$request->participant_id)->where('session_id',$request->session_id)->first();
        $old = $data;
        $data->delete();
        
        broadcast(new SessionEvent(new ParticipantListResource($old),'cancel'));
        return response()->json([
            'status' => true,
            'message' => 'Registration cancelled successfully',
            'data' => true
        ], 200);
    }

    public function csf(Request $request){
        $data = EventSessionParticipant::with('participant.detail')->where('participant_id',$request->participant_id)->where('session_id',$request->session_id)->first();
        $old = $data;
        $data->delete();
        
        broadcast(new SessionEvent(new ParticipantListResource($old),'cancel'));
        return response()->json([
            'status' => true,
            'message' => 'Registration cancelled successfully',
            'data' => true
        ], 200);
    }
}
