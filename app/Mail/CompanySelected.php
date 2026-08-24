<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanySelected extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $companyName;
    public $jobTitle;
    public $package;
    public $driveName;
    public $contactEmail;
    public $selectedDate;

    /**
     * Create a new message instance.
     */
    public function __construct($studentName, $companyName, $jobTitle, $package, $driveName, $contactEmail, $selectedDate)
    {
        $this->studentName = $studentName;
        $this->companyName = $companyName;
        $this->jobTitle = $jobTitle;
        $this->package = $package;
        $this->driveName = $driveName;
        $this->contactEmail = $contactEmail;
        $this->selectedDate = $selectedDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Offer Letter: Congratulations! You've been selected by {$this->companyName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.company-selected',
            with: [
                'studentName' => $this->studentName,
                'companyName' => $this->companyName,
                'jobTitle' => $this->jobTitle,
                'package' => $this->package,
                'driveName' => $this->driveName,
                'contactEmail' => $this->contactEmail,
                'selectedDate' => $this->selectedDate
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
