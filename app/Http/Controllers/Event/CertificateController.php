<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\EventSessionParticipant;
use App\Services\Certificates\CertificateTextFitter;
use Carbon\Carbon;

class CertificateController extends Controller
{
    /** The template is 2000x1414px; dompdf runs at 96 DPI, so the page is the same box in points. */
    private const PAGE = [0, 0, 1500, 1060.5];

    public function appreciation($session, $participant, CertificateTextFitter $fitter)
    {
        $data = EventSessionParticipant::with('participant', 'session.event.detail', 'session.venue', 'session.schedules')
            ->where('session_id', $session)
            ->where('participant_id', $participant)
            ->firstOrFail();

        $middleInitial = $data->participant->middlename
            ? strtoupper(mb_substr($data->participant->middlename, 0, 1)).'. '
            : '';
        $recipientName = trim($data->participant->firstname.' '.$middleInitial.$data->participant->lastname);

        $eventDate = Carbon::parse($data->session->schedules[0]->date);
        $issuedOn = Carbon::now();

        $bodySegments = [
            ['text' => 'For the active participation during the session ', 'style' => 'plain'],
            ['text' => '"'.$data->session->title.'"', 'style' => 'event'],
            ['text' => ' conducted as part of the ', 'style' => 'plain'],
            ['text' => $data->session->event->name, 'style' => 'event'],
            ['text' => ' held on ', 'style' => 'plain'],
            ['text' => $eventDate->format('d F Y'), 'style' => 'event'],
            ['text' => ' at ', 'style' => 'plain'],
            ['text' => $data->session->venue->name.', '.$data->session->event->detail->venue.'.', 'style' => 'venue'],
        ];

        $issueSegments = [
            ['text' => 'Given this ', 'style' => 'plain'],
            ['text' => $issuedOn->format('jS'), 'style' => 'event'],
            ['text' => $issuedOn->format(' \d\a\y \o\f F Y').' in Zamboanga City, Philippines.', 'style' => 'plain'],
        ];

        $pdf = \PDF::loadView('certificates.appreciation', [
            'recipientName' => $recipientName,
            'recipientFontSize' => $fitter->recipientFontSize($recipientName),
            'bodyFontSize' => $fitter->bodyFontSize($bodySegments),
            'bodySegments' => $bodySegments,
            'issueSegments' => $issueSegments,
        ])->setPaper(self::PAGE);

        return $pdf->stream('appreciation.pdf');
    }
}
