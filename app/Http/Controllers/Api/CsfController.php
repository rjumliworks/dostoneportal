<?php

namespace App\Http\Controllers\Api;

use App\Models\ListDropdown;
use Illuminate\Support\Facades\DB;
use App\Models\ParticipantPoint;
use App\Models\EventCsfQuestion;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventExhibitor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Data\FeedbackResource;
use App\Http\Resources\DefaultResource;
use App\Events\SessionEvent;
use App\Jobs\CertificateJob;

class CsfController extends Controller
{
    public function index(Request $request)
    {   
        $data = EventCsfQuestion::where('is_active',1)->where('is_rating',1)->get();
        return DefaultResource::collection($data);
    }

    public function questions(){
        $data = EventCsfQuestion::where('is_active', 1)->where('is_rating', 1)->get();
        return DefaultResource::collection($data);
    }

    // public function public(Request $request){
    //     $validated = $request->validate([
    //         'name' => 'nullable',
    //         'email' => 'required',
    //         'age' => 'required',
    //         'sex' => 'required',
    //         'comment' => 'required|string',
    //         'questions' => 'required|array|min:1',
    //         'questions.*.id' => 'required|integer|exists:csf_questions,id',
    //         'questions.*.rating' => 'required|integer|min:1|max:5',
    //     ]);

    //     // $session = EventSession::where('id',$request->session_id)->first();
    //     $ratings = collect($request->questions)->pluck('rating'); 
    //     $pub = new PublicCsfEntry;
    //     $pub->email = $request->email;
    //     $pub->name = $request->name;
    //     $pub->age = $request->age;
    //     $pub->sex = $request->sex;
    //     $pub->rate = round($ratings->avg(),1);
    //     $pub->comment = $request->comment;
    //     $pub->save();

    //     foreach($request->questions as $question){
    //         $pub->ratings()->create([
    //             'rating' => $question['rating'],
    //             'question_id' => $question['id']
    //         ]);
    //     }
    //     $pub->refresh();
    //     // broadcast(new SessionEvent($pub,'pub'));
    //     // $this->certificate($request->session_id,$request->participant_id);
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'CSF submitted successfully',
    //         'data' => $pub
    //     ], 200);
    // }

    // public function fb(Request $request){
        
    //     $pub = new Testing;
    //     $pub->email = $request->email;
    //     $pub->save();


    //     $pub->refresh();
    // broadcast(new SessionEvent($pub,'pub'));
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Error',
    //         'data' => $pub
    //     ], 200);
    // }

    public function session(Request $request){
        $validated = $request->validate([
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
        broadcast(new SessionEvent(new FeedbackResource($entry),'rating'));
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

    private function participation($session,$participant){
        $data = EventSessionParticipant::with('participant','session.venue','session.schedules','session.event.detail.municipality')->where('session_id',$session)->where('participant_id',$participant)->first(); 
        $array = [
            'data' => $data
        ]; 

        $pdf = \PDF::loadView('certificates.participation',$array)->setPaper('a4', 'portrait');
        $pdfContent = base64_encode($pdf->output());
        CertificateJob::dispatch($data->participant->email, $array, $pdfContent)->onConnection('database');
    }

    public function exhibitor(Request $request){
        $validated = $request->validate([
            'exhibitor_id' => 'required|exists:event_exhibitors,id',
            'participant_id' => 'required|exists:participants,id',
            'comment' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'required|integer|exists:csf_questions,id',
            'questions.*.rating' => 'required|integer|min:1|max:5',
        ]);

        $exhibitor = EventExhibitor::where('id',$request->exhibitor_id)->first();
        $ratings = collect($request->questions)->pluck('rating'); 
        $entry = $exhibitor->feedbackable()->create([
            'rate' => round($ratings->avg(),1),
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
        if($entry) {
            $engage = Dropdown::find(28);
            $point = ParticipantPoint::where('participant_id', $request->participant_id)->firstOrFail();

            $entry->engageable()->create([
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
        broadcast(new SessionEvent(new FeedbackResource($entry),'ex-rating'));
        return response()->json([
            'status' => true,
            'message' => 'CSF submitted successfully',
            'data' => new FeedbackResource($entry)
        ], 200);
    }

}
