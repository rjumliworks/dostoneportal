<?php

namespace App\Services\Certificates;

use App\Models\EventSession;
use App\Models\Participant;
use Carbon\Carbon;

/**
 * Builds the dynamic pieces (recipient name, coloured body/issued text) that
 * get laid over a masked certificate template. Shared by every certificate
 * type so the wording only differs by its opening phrase.
 */
class CertificateComposer
{
    /** The template is 2000x1414px; dompdf runs at 96 DPI, so the page is the same box in points. */
    public const PAGE = [0, 0, 1500, 1060.5];

    public function recipientName(Participant $participant): string
    {
        $middleInitial = $participant->middlename
            ? strtoupper(mb_substr($participant->middlename, 0, 1)).'. '
            : '';

        return trim($participant->firstname.' '.$middleInitial.$participant->lastname);
    }

    /**
     * @param  list<array{text: string, style: string}>  $opening
     * @return list<array{text: string, style: string}>
     */
    public function bodySegments(array $opening, EventSession $session): array
    {
        return [
            ...$opening,
            ['text' => $session->title, 'style' => 'event'],
            ['text' => ' session of ', 'style' => 'plain'],
            ['text' => $session->event->name, 'style' => 'event'],
            ['text' => ', held on ', 'style' => 'plain'],
            ['text' => Carbon::parse($session->schedules[0]->date)->format('j F Y'), 'style' => 'event'],
            ['text' => ' at ', 'style' => 'plain'],
            ['text' => $session->venue->establishment.', '.$session->venue->address.'.', 'style' => 'venue'],
        ];
    }

    /**
     * @return list<array{text: string, style: string}>
     */
    public function issueSegments(): array
    {
        $issuedOn = Carbon::now();

        return [
            ['text' => 'Given this ', 'style' => 'plain'],
            ['text' => $issuedOn->format('jS'), 'style' => 'event'],
            ['text' => $issuedOn->format(' \d\a\y \o\f F Y').' in Zamboanga City, Philippines.', 'style' => 'plain'],
        ];
    }
}
