<?php

namespace App\Http\Controllers\Api\Events;

use App\Models\Participant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Events\SessionEvent;
use App\Http\Resources\Api\Events\ParticipantResource;

class ParticipantController extends Controller
{
    public function participant(){
        $email = hash('sha256', $request->email);
        $participant = Participant::where('email_hash',$email)->first();
    }

    public function profile(Request $request){
        try {
            $participant = $request->user();

            $request->validate([
                'firstname' => 'required|string|max:100',
                'middlename' => 'required|string|max:50',
                'lastname' => 'required|string|max:100',
                'email' => [
                    'required',
                    'email',
                    'max:150',
                    function ($attribute, $value, $fail) use ($participant) {
                        $hash = hash('sha256', strtolower($value));
                        $exists = Participant::where('kradworkz', $hash)
                            ->where('id', '!=', $participant->id)
                            ->exists();
                        if ($exists) {
                            $fail('The email has already been taken.');
                        }
                    },
                ],
                'contact_no' => [
                    'required',
                    'numeric',
                    'digits:11',
                    function ($attribute, $value, $fail) use ($participant) {
                        $hash = hash('sha256', $value);
                        $exists = Participant::where('mobile_hash', $hash)
                            ->where('id', '!=', $participant->id)
                            ->exists();
                        if ($exists) {
                            $fail('The contact number has already been taken.');
                        }
                    },
                ],
            ]);

            $participant->firstname = $request->firstname;
            $participant->middlename = $request->middlename;
            $participant->lastname = $request->lastname;
            $participant->email = $request->email;
            $participant->mobile = $request->contact_no;
            $participant->save();

            $participant->load(['detail.sex', 'detail.type']);

            broadcast(new SessionEvent($participant, 'profile'));

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => new ParticipantResource($participant)
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
