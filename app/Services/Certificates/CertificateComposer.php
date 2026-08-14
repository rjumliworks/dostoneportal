<?php

namespace App\Services\Certificates;

use App\Models\EventSession;
use App\Models\EventSessionSchedule;
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
            ['text' => $this->dateRangeText($session->schedules), 'style' => 'event'],
            ['text' => ' at ', 'style' => 'plain'],
            ['text' => $session->venue->establishment.', '.$session->venue->address.'.', 'style' => 'venue'],
        ];
    }

    /**
     * Collapses a session's schedule dates into a single readable string, e.g.
     * "12 August 2026" for a one-day session, "12-14 August 2026" when the
     * days share a month, or "30 August - 2 September 2026" when they don't.
     * Pass $dayFirst = false for the "August 12-14, 2026" ordering instead.
     *
     * @param  iterable<EventSessionSchedule>  $schedules
     */
    public function dateRangeText(iterable $schedules, bool $dayFirst = true): string
    {
        $dates = collect($schedules)->map(fn ($schedule) => Carbon::parse($schedule->date))->sort();

        $start = $dates->first();
        $end = $dates->last();

        if ($start->isSameDay($end)) {
            return $dayFirst ? $start->format('j F Y') : $start->format('F d, Y');
        }

        if ($start->isSameMonth($end)) {
            return $dayFirst
                ? $start->format('j').'-'.$end->format('j F Y')
                : $start->format('F d').'-'.$end->format('d, Y');
        }

        if ($start->isSameYear($end)) {
            return $dayFirst
                ? $start->format('j F').' - '.$end->format('j F Y')
                : $start->format('F d').' - '.$end->format('F d, Y');
        }

        return $dayFirst
            ? $start->format('j F Y').' - '.$end->format('j F Y')
            : $start->format('F d, Y').' - '.$end->format('F d, Y');
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
