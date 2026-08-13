<?php

namespace App\Services\Events\Session;

use Hashids\Hashids;
use App\Models\EventSession;
use App\Models\EventCsfQuestion;
use App\Models\EventSessionParticipant;
use App\Models\Participant;
use App\Services\Certificates\CertificateComposer;

class CsfClass
{
    public function view($key)
    {
        $hashids = new Hashids('krad', 10);
        $id = $hashids->decode($key)[0] ?? null;

        $session = EventSession::with('venue', 'event')->findOrFail($id);

        $questions = EventCsfQuestion::where('is_active', 1)
            ->where('is_rating', 1)
            ->orderBy('sequence')
            ->get(['id', 'name', 'sequence']);

        return [
            'key' => $key,
            'title' => $session->title,
            'event' => [
                'name' => $session->event?->name,
            ],
            'venue' => [
                'establishment' => $session->venue?->establishment,
                'address' => $session->venue?->address,
            ],
            'questions' => $questions,
        ];
    }

    /**
     * A typed email that matches an existing Participant (via the same
     * kradworkz hash lookup the mobile/registration flow uses) attaches the
     * entry to that real participant instead of creating a guest row —
     * keeps a walk-in who's already registered from fragmenting their CSF
     * history across two identities.
     */
    public function submit($request, $key)
    {
        $hashids = new Hashids('krad', 10);
        $id = $hashids->decode($key)[0] ?? null;

        $session = EventSession::findOrFail($id);

        $email = strtolower($request->email);
        $rate = collect($request->questions)->pluck('rating')->avg();

        $participant = Participant::where('kradworkz', hash('sha256', $email))->first();

        if ($participant) {
            $entry = $session->feedbackable()->updateOrCreate(
                ['participant_id' => $participant->id],
                ['rate' => $rate, 'comment' => $request->comment]
            );
        } else {
            $entry = $session->feedbackable()->updateOrCreate(
                ['guest_email' => $email],
                [
                    'rate' => $rate,
                    'comment' => $request->comment,
                    'guest_name' => $this->formatName($request->name),
                    'guest_affiliation' => $request->affiliation,
                    'guest_designation' => $request->designation,
                ]
            );
        }

        $entry->ratings()->delete();
        foreach ($request->questions as $question) {
            $entry->ratings()->create([
                'rating' => $question['rating'],
                'question_id' => $question['id'],
            ]);
        }

        $this->sendCertificates($session, $participant, $request, $email);

        return $entry;
    }

    /**
     * The public CSF QR is only ever displayed at the venue, so a submission
     * there counts as proof of attendance even without a formal
     * EventSessionParticipant registration row — everyone who submits gets
     * the certificate emails, whether they're a matched-but-unregistered
     * Participant or a fully anonymous walk-in.
     */
    private function sendCertificates(EventSession $session, ?Participant $participant, $request, string $email): void
    {
        $certificate = new CertificateClass();

        $isRegistered = $participant && EventSessionParticipant::where('session_id', $session->id)
            ->where('participant_id', $participant->id)
            ->exists();

        if ($isRegistered) {
            $certificate->send($session->id, $participant->id);
            return;
        }

        $recipientName = $participant
            ? (new CertificateComposer())->recipientName($participant)
            : $this->formatName($request->name);

        $affiliationName = $participant
            ? $participant->detail?->affiliation?->name
            : $request->affiliation;

        $certificate->sendAdhoc($session, $recipientName, $email, $affiliationName);
    }

    /**
     * Typed-in guest names have no encrypted-mutator capitalization to fall
     * back on the way a registered Participant's firstname/lastname do (see
     * Participant::getFirstnameAttribute's ucwords()) — normalize here so a
     * walk-in's certificate doesn't print whatever casing they happened to
     * type (all lowercase, all caps, etc).
     */
    private function formatName(string $name): string
    {
        return ucwords(strtolower(trim(preg_replace('/\s+/', ' ', $name))));
    }
}
