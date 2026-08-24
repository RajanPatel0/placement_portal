<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyShortlisted extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $companyName;
    public $driveName;
    public $contactEmail;
    public $nextSteps;
    public $shortlistedDate;

    /**
     * Create a new message instance.
     */
    public function __construct($studentName, $companyName, $driveName, $nextSteps, $contactEmail, $shortlistedDate)
    {
        $this->studentName = $studentName;
        $this->companyName = $companyName;
        $this->driveName = $driveName;
        $this->nextSteps = $nextSteps;
        $this->contactEmail = $contactEmail;
        $this->shortlistedDate = $shortlistedDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Congratulations! You've been shortlisted by {$this->companyName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.company-shortlisted', // Fixed: changed 'email' to 'emails'
            with: [
                'studentName' => $this->studentName,
                'companyName' => $this->companyName,
                'driveName' => $this->driveName,
                'nextSteps' => $this->nextSteps,
                'contactEmail' => $this->contactEmail,
                'shortlistedDate' => $this->shortlistedDate
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
