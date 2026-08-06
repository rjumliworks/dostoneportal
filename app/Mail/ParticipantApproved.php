<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParticipantApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $session;

    public function __construct($name, $session = null)
    {
        $this->name = $name;
        $this->session = $session;
    }

    public function build()
    {
        return $this->subject('Pre-Registration Approved – Confirmation of Attendance Required')
            ->view('emails.registration.approved')
            ->with([
                'name' => $this->name,
                'session' => $this->session,
            ]);
    }
}
