<?php

namespace App\Services\Events\Session;

use Hashids\Hashids;
use Illuminate\Support\Facades\Crypt;
use App\Models\EventSession;
use App\Models\EventCsfEntry;
use App\Models\EventCsfQuestion;
use App\Models\EventExhibitor;
use App\Models\ListStatus;
use App\Http\Resources\SessionViewResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class PrintClass
{
    public function csf($request)
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

    public function attendance($request){
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);
        $id = $request->id;
        $data = EventSession::with('attendees.participant.detail.sex','venue','schedules','managers','event')->where('id',$id)->first();

        foreach ($data->attendees as $attendee) {
            if (!empty($attendee->participant->detail->signature)) {
                $attendee->participant->detail->signature_base64 = $this->convertToBase64($attendee->participant->detail->signature);
            }
            if (!empty($attendee->image)) {
                $attendee->image_base64 = $this->convertToBase64($attendee->image);
            }
        }

        // Avatars are stored as full S3 URLs, so converting them one at a time
        // in the loop above (like signature/image) means a blocking network
        // request per attendee. For large sessions that alone can run past
        // nginx's proxy timeout and the browser sees a 504 even though PHP's
        // own execution time limit is disabled above. Fetch them concurrently
        // instead, same approach as the participants/reservees print.
        $this->attachAttendeeAvatars($data->attendees);

        $url = $_SERVER['HTTP_HOST'].'/verification/documents/'.$data->code;
        $result = new Builder(
            writer: new PngWriter(),
            data: $url,
            size: 300,
            margin: 10,
        );

        $qrCodeImageString = $result->build()->getString();
        $base64Image = 'data:image/png;base64,' . base64_encode($qrCodeImageString);

        $array = [
            'qrCodeImage' => $base64Image,
            'date' => $this->dateRangeText($data->schedules),
            'head' => $data->managers->firstWhere('type', 'Head'),
            'data' => $data
        ]; 

        $pdf = \PDF::loadView('prints.attendance',$array)->setPaper('a4', 'landscape');
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "PAGE $pageNumber OF $pageCount";
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 7;
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = 60; // left margin
            $y = $canvas->get_height() - 45; // 20pt from bottom
            $canvas->text($x, $y, $text, $font, $size);
        });
        return $pdf->stream(strtolower($data->title).'-attendance.pdf');
    }

    public function attendanceExcel($request){
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);
        $id = $request->id;
        $data = EventSession::with('attendees.participant.detail.sex')->where('id', $id)->first();

        return Excel::download(new AttendanceExport($data->attendees), strtolower($data->title).'-attendance.xlsx');
    }

    public function participants($request){
        return $this->printParticipantList($request, 'participants');
    }

    public function reservees($request){
        return $this->printParticipantList($request, 'reservees');
    }

    // Participants and Reservees now print as separate PDFs (one per tab)
    // instead of one combined document, so this only builds/renders the list
    // the caller actually asked for.
    private function printParticipantList($request, $type){
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);

        $hashids = new Hashids('krad',10);
        $key = $hashids->decode($request->id);

        $data = EventSession::with([
                'venue','schedules','event',
                'participants' => function ($q) {
                    $q->orderBy('created_at', 'ASC');
                },
                'participants.participant.detail',
                'participants.status',
            ])
            ->where('id', $key[0])->first();

        $reservedList = $data->participants->filter(function ($item) {
            return optional($item->status)->name === 'Reserved';
        })->values();

        $mainList = $data->participants->reject(function ($item) {
            return optional($item->status)->name === 'Reserved';
        })->values();

        $statusName = null;

        if ($type === 'participants' && $request->filled('status')) {
            $mainList = $mainList->filter(function ($item) use ($request) {
                return optional($item->status)->id == $request->status;
            })->values();

            $statusName = optional(ListStatus::find($request->status))->name;
        }

        // Only fetch avatars for the list being printed, not the whole session.
        $this->attachAvatarImages($type === 'reservees' ? $reservedList : $mainList);

        $array = [
            'date' => $this->dateRangeText($data->schedules),
            'printedAt' => now()->format('F j, Y g:i A'),
            'data' => $data,
            'mainList' => $mainList,
            'reservedList' => $reservedList,
            'type' => $type,
            'statusName' => $statusName,
        ];

        $pdf = \PDF::loadView('prints.participants', $array)->setPaper('a4', 'landscape');
        $suffix = $type === 'reservees' ? 'reservees' : 'participants';
        return $pdf->stream(strtolower($data->title).'-'.$suffix.'.pdf');
    }

    public function links($request){
        $ids = array_filter(explode(',', (string) $request->ids));
        $sessions = EventSession::whereIn('id', $ids)->orderBy('title')->get();

        $hashids = new Hashids('krad', 10);

        $data = $sessions->map(function ($session) use ($hashids) {
            $key = $hashids->encode($session->id);
            $encryptedKey = Crypt::encryptString($key);

            return [
                'title' => $session->title,
                'registration' => config('app.registration_url') . '/registration/' . $encryptedKey,
                'vip' => config('app.registration_url') . '/registration/bPZBcQqTBHnfTUMG4qPQvA/' . $encryptedKey,
                'attendance' => config('app.registration_url') . '/session/' . $key,
            ];
        });

        $pdf = \PDF::loadView('prints.session-links', [
            'sessions' => $data,
            'printedAt' => now()->format('F j, Y g:i A'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('session-links.pdf');
    }

    private  function dateRangeText($schedules) {
        $start = $schedules[0]['date'];
        $end   = $schedules[0]['date'];

        foreach ($schedules as $s) {
            if ($s['date'] < $start) {
                $start = $s['date'];
            }
            if ($s['date'] > $end) {
                $end = $s['date'];
            }
        }

        // Format date
        $formatDate = function($dateStr) {
            return date("F j, Y", strtotime($dateStr));
        };

        return $start === $end
            ? $formatDate($start)
            : $formatDate($start) . " - " . $formatDate($end);
    }

    // Participant avatars are stored as full S3 URLs, so convertToBase64()
    // falls through to a network fetch for every one of them. Fetching those
    // one at a time (as the participants print used to) took well over a
    // second per avatar and blew past PHP's execution time limit for
    // sessions with 70+ registrants. Http::pool() fires them concurrently
    // instead, so the wait is bounded by the slowest single request.
    private function attachAvatarImages($participants)
    {
        $withAvatar = $participants->filter(fn ($item) => !empty($item->participant->detail->avatar));

        $remote = $withAvatar->filter(fn ($item) => filter_var($item->participant->detail->avatar, FILTER_VALIDATE_URL));
        $local = $withAvatar->reject(fn ($item) => filter_var($item->participant->detail->avatar, FILTER_VALIDATE_URL));

        foreach ($local as $item) {
            $item->participant->detail->avatar_base64 = $this->convertToBase64($item->participant->detail->avatar);
        }

        if ($remote->isEmpty()) {
            return;
        }

        $responses = Http::pool(fn ($pool) => $remote->map(
            fn ($item) => $pool->as($item->id)->timeout(15)->get($item->participant->detail->avatar)
        )->all());

        foreach ($remote as $item) {
            $response = $responses[$item->id] ?? null;

            if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                $mime = $response->header('Content-Type') ?: 'image/jpeg';
                $item->participant->detail->avatar_base64 = 'data:' . $mime . ';base64,' . base64_encode($response->body());
            }
        }
    }

    private function attachAttendeeAvatars($attendees)
    {
        $withAvatar = $attendees->filter(fn ($a) => !empty($a->participant->detail->avatar));

        $remote = $withAvatar->filter(fn ($a) => filter_var($a->participant->detail->avatar, FILTER_VALIDATE_URL));
        $local = $withAvatar->reject(fn ($a) => filter_var($a->participant->detail->avatar, FILTER_VALIDATE_URL));

        foreach ($local as $attendee) {
            $attendee->participant->detail->avatar_base64 = $this->convertToBase64($attendee->participant->detail->avatar);
        }

        if ($remote->isEmpty()) {
            return;
        }

        $responses = Http::pool(fn ($pool) => $remote->map(
            fn ($a) => $pool->as($a->id)->timeout(15)->get($a->participant->detail->avatar)
        )->all());

        foreach ($remote as $attendee) {
            $response = $responses[$attendee->id] ?? null;

            if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                $mime = $response->header('Content-Type') ?: 'image/jpeg';
                $attendee->participant->detail->avatar_base64 = 'data:' . $mime . ';base64,' . base64_encode($response->body());
            }
        }
    }

    private function convertToBase64($path)
    {
        // If you store public files like: storage/app/public/signatures/filename.png
        // and you saved the DB value like: signatures/filename.png
        if (Storage::disk('public')->exists($path)) {
            $file = Storage::disk('public')->get($path);
            $mime = Storage::disk('public')->mimeType($path);
            return 'data:' . $mime . ';base64,' . base64_encode($file);
        }

        // Attendance captures were previously written to
        // participants/{code}/attendance/{filename} while the DB recorded
        // images/participants/{code}/{filename}. Older rows still point at
        // that mismatched path, so fall back to the actual legacy location
        // instead of moving/renaming anything on disk.
        if (preg_match('#^images/participants/([^/]+)/([^/]+)$#', $path, $m)) {
            $legacyPath = 'participants/'.$m[1].'/attendance/'.$m[2];
            if (Storage::disk('public')->exists($legacyPath)) {
                $file = Storage::disk('public')->get($legacyPath);
                $mime = Storage::disk('public')->mimeType($legacyPath);
                return 'data:' . $mime . ';base64,' . base64_encode($file);
            }
        }

        // If you stored a full URL instead of a storage path:
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            try {
                $file = file_get_contents($path);
                $mime = @mime_content_type($path) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode($file);
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }
}
