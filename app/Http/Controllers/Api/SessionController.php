<?php

namespace App\Http\Controllers\Api;

use App\Jobs\CertificateJob;
use App\Events\SessionEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EventSession;
use App\Models\EventSessionQuestion;
use App\Models\EventSessionParticipant;
use App\Http\Resources\Api\Events\Session\FeedbackResource;
use App\Http\Resources\Api\Events\Session\QuestionResource;
use App\Http\Resources\Api\Events\Session\ParticipantResource;


class SessionController extends Controller
{
    public function registration(Request $request){
        $data = EventSessionParticipant::create([
            'status_id' => 52,
            'participant_id' => $request->participant_id,
            'session_id' => $request->session_id,
        ]);
        $data = EventSessionParticipant::with('participant.detail')->where('id',$data->id)->first();
        broadcast(new SessionEvent(new ParticipantResource($data),'register'));
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

}
