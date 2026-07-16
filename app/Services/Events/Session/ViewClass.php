<?php

namespace App\Services\Events\Session;

use Hashids\Hashids;
use App\Models\EventCsfEntry;
use App\Models\EventCsfQuestion;
use App\Models\EventSession;
use App\Models\EventExhibitor;
use App\Http\Resources\Event\Session\ViewResource;
use App\Http\Resources\SessionViewResource;

class ViewClass
{
     public function lists($request){
        $data = ViewResource::collection(
            EventSession::with('venue','detail','schedules','attendees.participant','status','activities.speaker','managers.user.profile')
            ->with('participants.participant.detail','feedbackable.participant.detail')
            ->with('event.detail.region:code,name,region','event.detail.province:code,name','event.detail.municipality:code,name','event.detail.barangay:code,name')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->whereHas('event', function($query){
                $query->where('is_active',1);
            })
            ->whereHas('managers', function($query){
                $query->where('user_id',\Auth::user()->id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($id){
       
        $hashids = new Hashids('krad',10);
        $key = $hashids->decode($id);

        $data = new ViewResource(
           EventSession::with([
                'venue','detail','schedules',
                'participants.participant.detail',
                'participants.participant.csfs' => function ($q) use ($key) {
                    $q->where('feedbackable_type', EventSession::class)
                    ->where('feedbackable_id', $key[0]);
                },
                'attendees' => function ($q) {
                    $q->orderBy('attended_at', 'DESC');
                },
                'attendees.participant',
                'status','activities.speaker',
                'managers.user.profile',
                'event.detail.region:code,name,region',
                'event.detail.province:code,name',
                'event.detail.municipality:code,name',
                'event.detail.barangay:code,name',
                'questions.participant.detail'
            ])
            ->where('id',$key[0])->first()
        );
        return $data;
    }

    public function print($request)
    {
        $id = $request->id;

        if ($request->type === 'session') {
            $session = EventSession::with('feedbackable')->where('id',$id)->first();
            $type = 'App\\Models\\EventSession';
        } else {
            $session = EventExhibitor::with('feedbackable')->where('id',$id)->first();
            $type = 'App\\Models\\EventExhibitor';
        }

        $questions = EventCsfQuestion::where('is_rating', 1)
        ->with(['ratings' => function ($q) use ($id, $type) {
            $q->whereHas('csf', function ($csf) use ($id, $type) {
                $csf->where('feedbackable_type', $type)
                    ->where('feedbackable_id', $id);
            });
        }])
        ->get();

        $participantCount = EventCsfEntry::where('feedbackable_type', $type)
        ->where('feedbackable_id', $id)
        ->count();

        // ✅ Compute overall customer satisfaction (average of all questions)
        $grandTotalScore = 0;
        $grandTotalResponses = 0;

        foreach ($questions as $question) {
            $count5 = $question->ratings->where('rating', 5)->count();
            $count4 = $question->ratings->where('rating', 4)->count();
            $count3 = $question->ratings->where('rating', 3)->count();
            $count2 = $question->ratings->where('rating', 2)->count();
            $count1 = $question->ratings->where('rating', 1)->count();

            $totalCount = $count1 + $count2 + $count3 + $count4 + $count5;
            $totalScore = ($count5 * 5) + ($count4 * 4) + ($count3 * 3) + ($count2 * 2) + ($count1 * 1);

            $grandTotalScore += $totalScore;
            $grandTotalResponses += $totalCount;
        }

        $overallAverage = $grandTotalResponses > 0 ? $grandTotalScore / $grandTotalResponses : 0;

        $pdf = \PDF::loadView('prints.csf', [
            'session' => $session->title,
            'questions' => $questions,
            'participantCount' => $participantCount,
            'overallAverage' => $overallAverage,
            'comments' => $session->feedbackable
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($session->title . '.pdf');
    }

}
