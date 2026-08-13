<?php

namespace App\Services\Events\Session;

use App\Jobs\CertificateJob;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Services\Certificates\CertificateComposer;
use App\Services\Certificates\CertificateTextFitter;

class CertificateClass
{
    public function send($session, $participant)
    {
        $data = EventSessionParticipant::with('participant.detail.affiliation','session.event.detail.municipality','session.venue','session.schedules')->where('session_id',$session)->where('participant_id',$participant)->first();

        if (!$data) {
            return null;
        }

        $composer = new CertificateComposer();
        $fitter = new CertificateTextFitter();
        $recipientName = $composer->recipientName($data->participant);
        $recipientFontSize = $fitter->recipientFontSize($recipientName);
        $issueSegments = $composer->issueSegments();

        $appearanceSegments = $composer->bodySegments([
            ['text' => 'For ', 'style' => 'plain'],
            ['text' => 'attending', 'style' => 'emphasis'],
            ['text' => ' the ', 'style' => 'plain'],
        ], $data->session);

        $sessionDate = optional($data->session->schedules->first())->date;

        $pdf1 = \PDF::loadView('certificates.appearance', [
            'sessionId' => $session,
            'recipientName' => $recipientName,
            'recipientFontSize' => $recipientFontSize,
            'bodyFontSize' => $fitter->bodyFontSize($appearanceSegments),
            'bodySegments' => $appearanceSegments,
            'issueSegments' => $issueSegments,
            'affiliationName' => optional($data->participant->detail->affiliation)->name,
            'eventName' => $data->session->event->name,
            'venueText' => $data->session->venue->establishment.', '.$data->session->venue->address,
            'sessionDateText' => $sessionDate ? \Carbon\Carbon::parse($sessionDate)->format('F d, Y') : '',
        ])->setPaper('a4');
        $pdfContent1 = base64_encode($pdf1->output());

        $participationSegments = $composer->bodySegments([
            ['text' => 'For ', 'style' => 'plain'],
            ['text' => 'participating', 'style' => 'emphasis'],
            ['text' => ' in the ', 'style' => 'plain'],
        ], $data->session);

        $pdf2 = \PDF::loadView('certificates.participation', [
            'recipientName' => $recipientName,
            'recipientFontSize' => $recipientFontSize,
            'bodyFontSize' => $fitter->bodyFontSize($participationSegments),
            'bodySegments' => $participationSegments,
            'issueSegments' => $issueSegments,
        ])->setPaper(CertificateComposer::PAGE);
        $pdfContent2 = base64_encode($pdf2->output());

        $data->recipientName = $recipientName;

        CertificateJob::dispatch($data->participant->email, $data, $pdfContent1, $pdfContent2)->onConnection('database');

        return $data;
    }

    /**
     * Certificate path for the public/walk-in CSF form: the QR is only ever
     * shown at the venue, so a submission there is treated as proof of
     * attendance even without an EventSessionParticipant registration row —
     * covers both a matched Participant who isn't registered for this
     * specific session, and a fully anonymous guest (no Participant record
     * at all), using whatever name/affiliation/email they typed on the form.
     */
    public function sendAdhoc($session, string $recipientName, string $email, ?string $affiliationName = null)
    {
        if (!($session instanceof EventSession)) {
            $session = EventSession::with('event.detail.municipality', 'venue', 'schedules')->findOrFail($session);
        }

        $composer = new CertificateComposer();
        $fitter = new CertificateTextFitter();
        $recipientFontSize = $fitter->recipientFontSize($recipientName);
        $issueSegments = $composer->issueSegments();

        $appearanceSegments = $composer->bodySegments([
            ['text' => 'For ', 'style' => 'plain'],
            ['text' => 'attending', 'style' => 'emphasis'],
            ['text' => ' the ', 'style' => 'plain'],
        ], $session);

        $sessionDate = optional($session->schedules->first())->date;

        $pdf1 = \PDF::loadView('certificates.appearance', [
            'sessionId' => $session->id,
            'recipientName' => $recipientName,
            'recipientFontSize' => $recipientFontSize,
            'bodyFontSize' => $fitter->bodyFontSize($appearanceSegments),
            'bodySegments' => $appearanceSegments,
            'issueSegments' => $issueSegments,
            'affiliationName' => $affiliationName,
            'eventName' => $session->event->name,
            'venueText' => $session->venue->establishment.', '.$session->venue->address,
            'sessionDateText' => $sessionDate ? \Carbon\Carbon::parse($sessionDate)->format('F d, Y') : '',
        ])->setPaper('a4');
        $pdfContent1 = base64_encode($pdf1->output());

        $participationSegments = $composer->bodySegments([
            ['text' => 'For ', 'style' => 'plain'],
            ['text' => 'participating', 'style' => 'emphasis'],
            ['text' => ' in the ', 'style' => 'plain'],
        ], $session);

        $pdf2 = \PDF::loadView('certificates.participation', [
            'recipientName' => $recipientName,
            'recipientFontSize' => $recipientFontSize,
            'bodyFontSize' => $fitter->bodyFontSize($participationSegments),
            'bodySegments' => $participationSegments,
            'issueSegments' => $issueSegments,
        ])->setPaper(CertificateComposer::PAGE);
        $pdfContent2 = base64_encode($pdf2->output());

        $data = (object) ['session' => $session, 'recipientName' => $recipientName];

        CertificateJob::dispatch($email, $data, $pdfContent1, $pdfContent2)->onConnection('database');

        return $data;
    }
}
