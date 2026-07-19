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
use App\Http\Resources\Api\ParticipantListResource;
use Illuminate\Support\Facades\Crypt;
use App\Events\SessionEvent;

use App\Jobs\CertificateJob;

class SessionController extends Controller
{
    public function registration(Request $request){
        $data = EventSessionParticipant::create([
            'status_id' => 52,
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


    

}
