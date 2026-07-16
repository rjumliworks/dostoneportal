<?php

namespace App\Http\Controllers\Api;

use App\Models\ListDropdown;
use Illuminate\Support\Facades\DB;
use App\Models\ParticipantPoint;
use App\Models\EventExhibitor;
use App\Models\EventExhibitorVisitor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\Api\ExhibitorResource;
use App\Http\Resources\Api\ExhibitorViewResource;
use App\Events\SessionEvent;

class ExhibitorController extends Controller
{
    public function index(Request $request)
    {
        $participantId = $request->participant_id;

        $data = EventExhibitor::with('contact')
            ->whereHas('event', function ($query) {
                $query->where('is_active', 1);
            })
            ->with(['visitors' => function ($query) use ($participantId) {
                $query->where('participant_id', $participantId);
            }])
            ->get()
             ->map(function ($exhibitor) {
                $visitor = $exhibitor->visitors->first();
                $exhibitor->has_visited = $visitor ? true : false;
                $exhibitor->has_voted = $visitor ? (bool) $visitor->has_voted : false;
                unset($exhibitor->visitors); 
                return $exhibitor;
            });

        return DefaultResource::collection($data);
    }

    public function view(Request $request, $id){
        $participantId = $request->participant_id;

        $data = EventExhibitor::with('contact','feedbackable.participant.detail')
            ->withCount('visitors') // ✅ only gets the count, not full list
            ->find($id);

        if ($data) {
            // Check if this participant has visited
            $visitor = $data->visitors()
                ->where('participant_id', $participantId)
                ->first();

            $data->has_visited = (bool) $visitor;
            $data->has_voted   = $visitor ? (bool) $visitor->has_voted : false;
            $data->feedback = $data->feedbackable
                    ->where('participant_id', $participantId)
                    ->first(); 
            $data->feedbacks = $data->feedbackable;
        }

        return new ExhibitorViewResource($data);
    }


    public function attendance(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $visitor = EventExhibitorVisitor::where('participant_id', $request->participant_id)
                ->where('exhibitor_id', $request->exhibitor_id)
                ->first();

            if ($visitor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Attendance already recorded for this participant.'
                ], 400);
            }

            $visitor = $this->recordAttendance($request->participant_id, $request->exhibitor_id);

            return response()->json([
                'status'  => true,
                'message' => 'Attendance successfully recorded.',
                'data'    => $visitor
            ], 201);
        });
    }


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
                    // broadcast(new SessionEvent($data, 'minus'));
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
                // broadcast(new SessionEvent($data, 'plus'));
            }

            // Broadcast update
            $data = [
                'participant_id' => $request->participant_id,
                'id'             => $request->exhibitor_id,
                'status'         => $visitor->has_voted,
            ];
            // broadcast(new SessionEvent($data, 'vote'));

            return response()->json([
                'status'  => true,
                'message' => $visitor->has_voted
                    ? 'Vote submitted successfully!'
                    : 'Vote removed successfully!',
                'data'    => $visitor->has_voted,
            ], 200);
        });
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
