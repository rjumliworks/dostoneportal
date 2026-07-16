<?php

namespace App\Http\Controllers\Api;

use App\Models\Participant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\SessionEvent;

class ParticipantController extends Controller
{
    public function participant(){
        $email = hash('sha256', $request->email);
        $participant = Participant::where('email_hash',$email)->first();
    }

    public function profile(Request $request){
        $data = Participant::where('id',$request->participant_id)->first();
        $data->firstname = $request->firstname;
        $data->middlename = $request->middlename;
        $data->lastname = $request->lastname;
        $data->save();

        broadcast(new SessionEvent($data,'profile'));
        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $data
        ], 200);
    }
}
