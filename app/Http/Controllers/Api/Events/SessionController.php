<?php

namespace App\Http\Controllers\Api\Events;

use Illuminate\Support\Facades\Crypt;
use App\Jobs\CertificateJob;
use App\Events\SessionEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ListDropdown;
use App\Models\Participant;
use App\Models\ParticipantPoint;
use App\Models\EventSession;
use App\Models\EventExhibitor;
use App\Models\EventExhibitorVisitor;
use App\Models\EventSessionQuestion;
use App\Models\EventSessionParticipant;
use App\Http\Resources\Api\AttendanceResource;
use App\Http\Resources\Api\Events\Session\FeedbackResource;
use App\Http\Resources\Api\Events\Session\QuestionResource;
use App\Http\Resources\Api\Events\Session\ParticipantResource;


class SessionController extends Controller
{
    public function registration(Request $request){
        $data = EventSessionParticipant::create([
            'status_id' => ($request->is_exclusive) ? 52 : 58,
            'participant_id' => $request->participant_id,
            'session_id' => $request->session_id,
            'is_approved' => ($request->is_exclusive) ? 0 : 1,
        ]);
        $data = EventSessionParticipant::with('participant.detail')->where('id',$data->id)->first();
        broadcast(new SessionEvent(new ParticipantResource($data),'register'));
        if(!$request->is_exclusive){
            broadcast(new SessionEvent(new ParticipantResource($data),'approve'));
        }
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
        
        broadcast(new SessionEvent(new ParticipantResource($old),'cancel'));
        return response()->json([
            'status' => true,
            'message' => 'Registration cancelled successfully',
            'data' => true
        ], 200);
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

    public function feedback(Request $request){
        $request->validate([
            'session_id' => 'required|exists:event_sessions,id',
            'participant_id' => 'required|exists:participants,id',
            'comment' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'required|integer|exists:event_csf_questions,id',
            'questions.*.rating' => 'required|integer|min:1|max:5',
        ]);

        $session = EventSession::where('id',$request->session_id)->first();
        $ratings = collect($request->questions)->pluck('rating'); 
        $entry = $session->feedbackable()->create([
            'rate' => $ratings->avg(), 
            // 'rate' => round($ratings->avg(),1),
            'comment' => $request->comment,
            'participant_id' => $request->participant_id
        ]);
        foreach($request->questions as $question){
            $entry->ratings()->create([
                'rating' => $question['rating'],
                'question_id' => $question['id']
            ]);
        }
        $entry->refresh();
        broadcast(new SessionEvent(new FeedbackResource($entry),'feedback'));
        $this->certificate($request->session_id,$request->participant_id);
        // $this->participation($request->session_id,$request->participant_id);
        return response()->json([
            'status' => true,
            'message' => 'CSF submitted successfully',
            'data' => new FeedbackResource($entry)
        ], 200);
    }

    private function certificate($session,$participant){
        $data = EventSessionParticipant::with('participant','session.event.detail.municipality')->where('session_id',$session)->where('participant_id',$participant)->first(); 
        $array = [
            'data' => $data
        ]; 

        $pdf1 = \PDF::loadView('certificates.appearance',$array)->setPaper('a4', 'portrait');
        $pdfContent1 = base64_encode($pdf1->output());
        $pdf2 = \PDF::loadView('certificates.participation',$array)->setPaper('a4', 'portrait');
        $pdfContent2 = base64_encode($pdf2->output());
        CertificateJob::dispatch($data->participant->email, $array,$pdfContent1, $pdfContent2)->onConnection('database');
    }

    public function attendance(Request $request)
    {   
        $sessionCode = $request->session;
        $participant = Participant::select('id','firstname','lastname')
                    ->where('id', $request->participant_id)
                    ->first();
        try {
            // The exhibitor QR is generated with Crypt::encryptString() (see
            // App\Http\Resources\Event\Exhibitor\ViewResource), which skips
            // PHP serialization — must be reversed with decryptString(), not
            // plain decrypt() (which tries to unserialize the result and
            // fails on a plain code string).
            $aa = Crypt::decryptString($sessionCode);
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
                    $engage = ListDropdown::findOrFail(68);
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

            try {
                $code = Crypt::decrypt($cipher);
            } catch (\Exception) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired QR code.'
                ], 400);
            }

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

}
