<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\EventSessionParticipant;
use App\Services\Certificates\CertificateComposer;
use App\Services\Certificates\CertificateTextFitter;

class CertificateController extends Controller
{
    public function appreciation($session, $participant, CertificateComposer $composer, CertificateTextFitter $fitter)
    {
        $data = EventSessionParticipant::with('participant', 'session.event.detail', 'session.venue', 'session.schedules')
            ->where('session_id', $session)
            ->where('participant_id', $participant)
            ->firstOrFail();

        $recipientName = $composer->recipientName($data->participant);

        $bodySegments = $composer->bodySegments([
            ['text' => 'For the active participation during the session ', 'style' => 'plain'],
            ['text' => '"'.$data->session->title.'"', 'style' => 'event'],
            ['text' => ' conducted as part of the ', 'style' => 'plain'],
        ], $data);

        $pdf = \PDF::loadView('certificates.appreciation', [
            'recipientName' => $recipientName,
            'recipientFontSize' => $fitter->recipientFontSize($recipientName),
            'bodyFontSize' => $fitter->bodyFontSize($bodySegments),
            'bodySegments' => $bodySegments,
            'issueSegments' => $composer->issueSegments(),
        ])->setPaper(CertificateComposer::PAGE);

        return $pdf->stream('appreciation.pdf');
    }
}
