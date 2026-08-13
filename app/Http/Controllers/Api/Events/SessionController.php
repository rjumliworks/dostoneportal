<?php

namespace App\Http\Controllers\Api\Events;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\Rekognition\RekognitionClient;
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
use App\Models\EventSessionAttendance;
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
        (new \App\Services\Events\Session\CertificateClass())->send($session, $participant);
    }

    /**
     * Read-only pre-check run right after a QR scan, before the mobile app
     * opens the face-confirm camera — lets the frontend skip the selfie step
     * entirely when the participant already visited/attended, instead of
     * only discovering that after they've gone through face verification.
     */
    public function checkAttendance(Request $request)
    {
        $sessionCode = $request->session;

        try {
            $aa = Crypt::decryptString($sessionCode);
            $ex = EventExhibitor::where('code', $aa)->first();
        } catch (\Exception $e) {
            $ex = null;
        }

        if ($ex) {
            $already = EventExhibitorVisitor::where('exhibitor_id', $ex->id)
                ->where('participant_id', $request->participant_id)
                ->exists();

            return response()->json([
                'status' => true,
                'already' => $already,
                'message' => $already ? 'You already visited the exhibitor.' : null,
            ]);
        }

        $cipher = substr($sessionCode, 0, -10);

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

        $isRegistered = EventSessionParticipant::where('participant_id', $request->participant_id)
            ->where('session_id', $session_id)
            ->exists();

        if (!$isRegistered) {
            return response()->json([
                'status' => false,
                'message' => 'You are not a registered participant.'
            ], 400);
        }

        // Attendance is tracked per calendar day so multi-day sessions don't
        // block a participant just because they already checked in on an
        // earlier day of the same session.
        $alreadyToday = EventSessionAttendance::where('participant_id', $request->participant_id)
            ->where('session_id', $session_id)
            ->whereDate('date', now()->toDateString())
            ->whereNotNull('attended_at')
            ->exists();

        return response()->json([
            'status' => true,
            'already' => $alreadyToday,
            'message' => $alreadyToday ? 'Attendance already recorded for this participant today.' : null,
        ]);
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
                $verify = $this->verifyFace($request, $participant);
                if (!$verify['ok']) {
                    $data = [
                        'participant_id' => $request->participant_id,
                        'name' => $participant->firstname.' '.$participant->lastname,
                        'type' => 'not',
                        'message' => $this->faceVerificationMessage($verify['reason'])
                    ];
                    broadcast(new SessionEvent($data,'exhibit_visit'));
                    return response()->json([
                        'status' => false,
                        'message' => $this->faceVerificationMessage($verify['reason'])
                    ], 422);
                }

                $new = new EventExhibitorVisitor;
                $new->participant_id = $request->participant_id;
                $new->exhibitor_id = $ex->id;
                $new->image = $verify['path'];

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

            $registration = EventSessionParticipant::where('participant_id', $request->participant_id)
                ->where('session_id', $session_id)
                ->first();

            if (!$registration) {
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

            // Attendance is tracked per calendar day (event_session_attendances)
            // so a check-in on an earlier day of a multi-day session doesn't
            // block subsequent days — only today's record is checked here.
            $today = now()->toDateString();
            $todayAttendance = EventSessionAttendance::where('participant_id', $request->participant_id)
                ->where('session_id', $session_id)
                ->whereDate('date', $today)
                ->first();

            if ($todayAttendance && $todayAttendance->attended_at) {
                $participant = Participant::select('id','firstname','lastname')->where('id',$request->participant_id)->first();
                $data = [
                    'participant_id' => $request->participant_id,
                    'name' => $participant->firstname.' '.$participant->lastname,
                    'type' => 'already',
                    'message' => 'Your attendance has already been recorded for today'
                ];
                broadcast(new SessionEvent($data,'attendance-error',$randomkey));
                return response()->json([
                    'status' => false,
                    'message' => 'Attendance already recorded for this participant today.'
                ], 400);
            }

            $participant = Participant::select('id','firstname','lastname')->where('id',$request->participant_id)->first();
            $verify = $this->verifyFace($request, $participant);
            if (!$verify['ok']) {
                $data = [
                    'participant_id' => $request->participant_id,
                    'name' => $participant->firstname.' '.$participant->lastname,
                    'type' => 'not',
                    'message' => $this->faceVerificationMessage($verify['reason'])
                ];
                broadcast(new SessionEvent($data,'attendance-error',$randomkey));
                return response()->json([
                    'status' => false,
                    'message' => $this->faceVerificationMessage($verify['reason'])
                ], 422);
            }

            EventSessionAttendance::updateOrCreate(
                [
                    'participant_id' => $request->participant_id,
                    'session_id' => $session_id,
                    'date' => $today,
                ],
                [
                    'image' => $verify['path'],
                    'attended_at' => now(),
                ]
            );

            // Registration row keeps mirroring the latest attendance so
            // existing status/attended_at based reporting (capacity counts,
            // admin lists, attendees()) is unaffected by this change.
            $registration->image = $verify['path'];
            $registration->attended_at = now();
            $registration->status_id = 8;

            if ($registration->save()) {
                $latest = EventSessionParticipant::with('participant.detail','session')->where('session_id', $session_id)
                ->where('id', $registration->id)
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

    /**
     * Confirms the selfie captured right after a QR scan actually belongs to
     * the logged-in participant, not just whoever is holding the phone —
     * searches the Rekognition collection built at registration time and
     * requires the top match's ExternalImageId to equal the claimed
     * participant_id. On success the photo is persisted to the public disk
     * and its path returned for the caller to store alongside the
     * attendance/visit record.
     */
    private function verifyFace(Request $request, ?Participant $participant): array
    {
        $request->validate(['image' => 'required|image']);

        if (!$participant) {
            return ['ok' => false, 'reason' => 'no_participant'];
        }

        $file = $request->file('image');
        $tempFilename = uniqid().'.'.$file->getClientOriginalExtension();
        $s3Path = $file->storeAs('oneportal/temp', $tempFilename, 's3');

        $rekognition = new RekognitionClient([
            'version'     => 'latest',
            'region'      => config('services.rekognition.region'),
            'credentials' => [
                'key'    => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);

        try {
            $matches = $rekognition->searchFacesByImage([
                'CollectionId' => config('services.rekognition.participant_id'),
                'Image' => [
                    'S3Object' => [
                        'Bucket' => config('services.rekognition.bucket'),
                        'Name' => $s3Path,
                    ],
                ],
                'FaceMatchThreshold' => 90,
                'MaxFaces' => 1,
            ]);
        } catch (\Exception $e) {
            return ['ok' => false, 'reason' => 'no_face'];
        }

        $match = $matches['FaceMatches'][0] ?? null;
        if (!$match || (string) $match['Face']['ExternalImageId'] !== (string) $participant->id) {
            return ['ok' => false, 'reason' => 'mismatch'];
        }

        $filename = Str::random(10).'.'.$file->getClientOriginalExtension();
        $relativePath = 'participants/'.$participant->id.'/attendance/'.$filename;
        Storage::disk('public')->putFileAs('participants/'.$participant->id.'/attendance/', $file, $filename);

        return ['ok' => true, 'path' => $relativePath];
    }

    private function faceVerificationMessage(string $reason): string
    {
        return match ($reason) {
            'no_face' => 'No face was detected in the photo. Please make sure your face is clearly visible and try again.',
            'mismatch' => 'The face in the photo does not match your account. Please try again with your own face clearly visible.',
            'no_participant' => 'Participant not found.',
            default => 'Face verification failed. Please try again.',
        };
    }

}
