<?php

namespace App\Services\Events\Session;

use App\Jobs\CertificateJob;
use App\Models\EventSessionParticipant;
use App\Services\Certificates\CertificateComposer;
use App\Services\Certificates\CertificateTextFitter;

class CertificateClass
{
    public function send($session, $participant)
    {
        $data = EventSessionParticipant::with('participant','session.event.detail.municipality','session.venue','session.schedules')->where('session_id',$session)->where('participant_id',$participant)->first();

        $composer = new CertificateComposer();
        $fitter = new CertificateTextFitter();
        $recipientName = $composer->recipientName($data->participant);
        $recipientFontSize = $fitter->recipientFontSize($recipientName);
        $issueSegments = $composer->issueSegments();

        $appearanceSegments = $composer->bodySegments([
            ['text' => 'For ', 'style' => 'plain'],
            ['text' => 'attending', 'style' => 'emphasis'],
            ['text' => ' the ', 'style' => 'plain'],
        ], $data);

        $pdf1 = \PDF::loadView('certificates.appearance', [
            'sessionId' => $session,
            'recipientName' => $recipientName,
            'recipientFontSize' => $recipientFontSize,
            'bodyFontSize' => $fitter->bodyFontSize($appearanceSegments),
            'bodySegments' => $appearanceSegments,
            'issueSegments' => $issueSegments,
        ])->setPaper('a4');
        $pdfContent1 = base64_encode($pdf1->output());

        $participationSegments = $composer->bodySegments([
            ['text' => 'For ', 'style' => 'plain'],
            ['text' => 'participating', 'style' => 'emphasis'],
            ['text' => ' in the ', 'style' => 'plain'],
        ], $data);

        $pdf2 = \PDF::loadView('certificates.participation', [
            'recipientName' => $recipientName,
            'recipientFontSize' => $recipientFontSize,
            'bodyFontSize' => $fitter->bodyFontSize($participationSegments),
            'bodySegments' => $participationSegments,
            'issueSegments' => $issueSegments,
        ])->setPaper(CertificateComposer::PAGE);
        $pdfContent2 = base64_encode($pdf2->output());

        CertificateJob::dispatch($data->participant->email, $data, $pdfContent1, $pdfContent2)->onConnection('database');

        return $data;
    }
}
