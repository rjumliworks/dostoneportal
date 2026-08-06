<?php

namespace App\Services\Events\Session;

use Hashids\Hashids;
use App\Models\Participant;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Events\SessionEvent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Resources\Event\AttendanceResource;
use App\Http\Resources\Api\Events\Session\ParticipantResource;
use App\Http\Resources\Event\Session\ParticipantListResource;
use App\Jobs\ParticipantApprovedJob;

class UpdateClass
{
    public function participant($request){
        $data = EventSessionParticipant::with('participant','status')
        ->where('session_id',$request->session_id)
        ->where('participant_id',$request->participant_id)->first();
        $data->status_id = $request->status_id;
        $data->is_approved = $request->is_approved;
        $data->save();
        $data->refresh();

        $broadcastType = match ($request->action) {
            'approve' => 'approve',
            'promote' => 'promote',
            default => 'reject',
        };
        // broadcast(new SessionEvent(new ParticipantResource($data), $broadcastType));

        if ($request->action === 'approve') {
            ParticipantApprovedJob::dispatch($data->participant->email, $data->participant->name, $data->session_id)->onConnection('database');
            $data->approval_mailed_at = now();
            $data->save();
        }

        $message = match ($request->action) {
            'approve' => 'Participant successfully approved.',
            'reject' => 'Participant successfully rejected.',
            'promote' => 'Participant successfully promoted from the waitlist.',
            default => 'Participant status successfully updated.',
        };

        return [
            'data' => new ParticipantListResource($data),
            'message' => $message,
            'info' => 'success',
        ];
    }

    public function notifyApproved($request){
        $hashids = new Hashids('krad',10);
        $key = $hashids->decode($request->id);

        $session = EventSession::find($key[0]);
        if (!$session || !$session->is_exclusive) {
            return [
                'data' => 0,
                'message' => 'Bulk approval emails are only available for exclusive sessions.',
                'info' => 'error',
            ];
        }

        $pending = EventSessionParticipant::with('participant')
            ->where('session_id', $key[0])
            ->where('is_approved', 1)
            ->whereNull('approval_mailed_at')
            ->get();

        foreach ($pending as $data) {
            ParticipantApprovedJob::dispatch($data->participant->email, $data->participant->name, $data->session_id)->onConnection('database');
        }

        EventSessionParticipant::where('session_id', $key[0])
            ->where('is_approved', 1)
            ->whereNull('approval_mailed_at')
            ->update(['approval_mailed_at' => now()]);

        return [
            'data' => $pending->count(),
            'message' => $pending->count() > 0
                ? $pending->count().' approval email(s) queued for sending.'
                : 'No pending participants to notify.',
            'info' => 'success',
        ];
    }

    public function session($request){
        $data = EventSession::findOrFail($request->id);
        $data->update([
            'title'    => $request->title,
            'venue_id' => $request->venue_id,
        ]);

        if ($data->detail) {
            $data->detail->update([
                'capacity'    => $request->capacity,
                'description' => $request->description,
            ]);
        }

        return [
            'data' => $data,
            'message' => 'Event status successfully updated.', 
            'info' => "success"
        ];

    }

    public function status($request){
        $session_id = $request->id;
        $status_id = $request->status_id;

        $data = EventSession::with('status')->where('id',$session_id)->first();
        $data->status_id = $status_id;
        $data->save();
        $data = EventSession::with('status')->where('id',$session_id)->first();
        broadcast(new SessionEvent($data,'status'));
        return [
            'data' => $data->status,
            'message' => 'Event status successfully updated.', 
            'info' => "success"
        ];
    }

    public function attendance($request){
 
        $hashids = new Hashids('krad',10);
        $session_id = $hashids->decode($request->session);
        $code = $request->participant;

        $participant_id = Participant::where('code',$code)->value('id');
        $data = EventSessionParticipant::where('session_id', $session_id)->where('participant_id', $participant_id)->first();

        if (!$data) {
            $participant = Participant::select('id','firstname','lastname')->where('id',$participant_id)->first();
            $data = [
                'participant_id' => $request->participant_id,
                'name' => $participant->firstname.' '.$participant->lastname,
                'type' => 'not'
            ];
            // broadcast(new SessionEvent($data,'attendance-error',$request->code));
            return [
                'data' => '-',
                'message' => 'Activity successfully created.', 
                'info' => "false"
            ];
        }

        if($data->attended_at) {
            if($data->image){
                $participant = Participant::select('id','firstname','lastname')->where('id',$participant_id)->first();
                $data = [
                    'participant_id' => $participant_id,
                    'name' => $participant->firstname.' '.$participant->lastname,
                    'type' => 'already'
                ];
                // broadcast(new SessionEvent($data,'attendance-error',$request->code));
                 return [
                    'data' => '-',
                    'message' => 'Activity successfully created.', 
                    'info' => "false"
                ];
            }
        }

        if($request->image){
            $path = $this->image($request);
            $data->image = $path;
        }
        if(!$data->attended_at){
            $data->attended_at = now();
            $data->status_id = 8;
        }
        if($data->save()){
            if($request->image){
                $latest = EventSessionParticipant::with('participant')->where('session_id', $session_id)
                ->where('participant_id', $participant_id)
                ->first();
                // broadcast(new SessionEvent(new AttendanceResource($latest),'attendance-image',$request->code));
            }
        }

        $latest = EventSessionParticipant::with('participant')->where('session_id', $session_id)
        ->where('participant_id', $participant_id)
        ->first();

        return [
            'data' => new AttendanceResource($latest),
            'message' => 'Activity successfully created.', 
            'info' => "success"
        ];

    }

    public function image($request)
    {
        $image = $request->input('image'); // base64 string

        // Validate format
        if (!preg_match('/^data:image\/(\w+);base64,/', $image, $matches)) {
            return response()->json(['error' => 'Invalid image format.'], 422);
        }

        $type = strtolower($matches[1]); // png, jpg, jpeg, gif
        if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
            return response()->json(['error' => 'Invalid image type.'], 422);
        }

        // Remove header and decode
        $image = substr($image, strpos($image, ',') + 1);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        if ($imageData === false) {
            return response()->json(['error' => 'Base64 decode failed.'], 422);
        }

        // Save to storage/app/public/images/attendance
        $filename = Str::random(10) . '.' . $type;
        $path = 'images/attendance/' . $filename;
        Storage::disk('public')->put($path, $imageData);

        return $path;
    }
}
