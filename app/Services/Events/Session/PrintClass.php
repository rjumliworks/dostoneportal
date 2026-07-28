<?php

namespace App\Services\Events\Session;

use Hashids\Hashids;
use App\Models\EventSession;
use App\Models\EventCsfEntry;
use App\Models\EventCsfQuestion;
use App\Models\EventExhibitor;
use App\Http\Resources\SessionViewResource;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

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
                $attendee->participant->detail->signature_base64 = ($attendee->participant->detail->signature) ? $this->convertToBase64($attendee->participant->detail->signature) : null;
            }
            if (!empty($attendee->image)) {
                $attendee->image_base64 = ($attendee->image) ? $this->convertToBase64($attendee->image) : null;
            }
            if (!empty($attendee->participant->detail->avatar)) {
                $attendee->participant->detail->avatar_base64 = ($attendee->participant->detail->avatar) ? $this->convertToBase64($attendee->participant->detail->avatar) : null;
            }
        }

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

    public function participants($request){
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

        foreach ($data->participants as $item) {
            if (!empty($item->participant->detail->avatar)) {
                $item->participant->detail->avatar_base64 = $this->convertToBase64($item->participant->detail->avatar);
            }
        }

        $reservedList = $data->participants->filter(function ($item) {
            return optional($item->status)->name === 'Reserved';
        })->values();

        $mainList = $data->participants->reject(function ($item) {
            return optional($item->status)->name === 'Reserved';
        })->values();

        $array = [
            'date' => $this->dateRangeText($data->schedules),
            'printedAt' => now()->format('F j, Y g:i A'),
            'data' => $data,
            'mainList' => $mainList,
            'reservedList' => $reservedList,
        ];

        $pdf = \PDF::loadView('prints.participants', $array)->setPaper('a4', 'landscape');
        return $pdf->stream(strtolower($data->title).'-participants.pdf');
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

    private function convertToBase64($path)
    {
        // If you store public files like: storage/app/public/signatures/filename.png
        // and you saved the DB value like: signatures/filename.png
        if (Storage::disk('public')->exists($path)) {
            $file = Storage::disk('public')->get($path);
            $mime = Storage::disk('public')->mimeType($path);
            return 'data:' . $mime . ';base64,' . base64_encode($file);
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
