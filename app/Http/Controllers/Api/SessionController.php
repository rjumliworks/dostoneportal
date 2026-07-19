<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EventSessionParticipant;
use App\Http\Resources\Api\Events\Session\ParticipantResource;
use App\Events\SessionEvent;


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


    

}
