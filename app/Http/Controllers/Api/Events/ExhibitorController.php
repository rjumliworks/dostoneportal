<?php

namespace App\Http\Controllers\Api\Events;

use Illuminate\Http\Request;
use App\Events\SessionEvent;
use App\Models\ListDropdown;
use App\Models\EventExhibitor;
use App\Models\ParticipantPoint;
use Illuminate\Support\Facades\DB;
use App\Models\EventExhibitorVisitor;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Events\Exhibitor\FeedbackResource;

class ExhibitorController extends Controller
{
    public function vote(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $visitor = EventExhibitorVisitor::where('participant_id', $request->participant_id)
                ->where('exhibitor_id', $request->exhibitor_id)
                ->first();

            if (! $visitor) {
                $visitor = $this->recordAttendance($request->participant_id, $request->exhibitor_id);
            }
            

            $engage = ListDropdown::findOrFail(69);
            $point = ParticipantPoint::where('participant_id', $request->participant_id)->firstOrFail();

            if ($visitor->has_voted) {
                // 👎 Unvote
                $visitor->update([
                    'has_voted' => false,
                    'voted_at'  => null,
                ]);

                // delete engageable record and subtract points
                $engageable = $visitor->engageable()
                    ->where('type_id', $engage->id)
                    ->where('point_id', $point->id)
                    ->latest()
                    ->first();

                if ($engageable) {
                    $point->points -= $engageable->points;
                    $point->save();
                    $engageable->delete();
                    $data = [
                        'participant_id'        => $request->participant_id,
                        'points'    => $engageable->points
                    ];
                    broadcast(new SessionEvent($data, 'minus'));
                    $data2 = [
                        'exhibitor_id'        => $request->exhibitor_id,
                    ];
                    broadcast(new SessionEvent($data2, 'minus-ex'));
                }
            } else {
                // 👍 Vote
                $visitor->update([
                    'has_voted' => true,
                    'voted_at'  => now(),
                ]);

                $visitor->engageable()->create([
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
                broadcast(new SessionEvent(['id' => $request->exhibitor_id], 'plus-ex'));
            }

            // Broadcast update
            $data = [
                'participant_id' => $request->participant_id,
                'id'             => $request->exhibitor_id,
                'status'         => $visitor->has_voted,
            ];
            broadcast(new SessionEvent($data, 'vote'));

            return response()->json([
                'status'  => true,
                'message' => $visitor->has_voted
                    ? 'Vote submitted successfully!'
                    : 'Vote removed successfully!',
                'data'    => $visitor->has_voted,
            ], 200);
        });
    }

    public function feedback(Request $request){
        $request->validate([
            'exhibitor_id' => 'required|exists:event_exhibitors,id',
            'participant_id' => 'required|exists:participants,id',
            'comment' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'required|integer|exists:event_csf_questions,id',
            'questions.*.rating' => 'required|integer|min:1|max:5',
        ]);

        $exhibitor = EventExhibitor::where('id',$request->exhibitor_id)->first();
        $ratings = collect($request->questions)->pluck('rating'); 
        $entry = $exhibitor->feedbackable()->create([
            'rate' => $ratings->avg(), 
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
            $engage = ListDropdown::find(70);
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

    private function recordAttendance($participantId, $exhibitorId)
    {
        $attendance = EventExhibitorVisitor::firstOrCreate(
            [
                'participant_id' => $participantId,
                'exhibitor_id'   => $exhibitorId,
            ]
        );
        if($attendance) {
            $engage = ListDropdown::find(68);
            $point = ParticipantPoint::where('participant_id', $participantId)->firstOrFail();

            $attendance->engageable()->create([
                'points'   => $engage->others,
                'type_id'  => $engage->id,
                'point_id' => $point->id,
            ]);

            $point->points += $engage->others;
            $point->save();
        }

        return $attendance;
    }
}
