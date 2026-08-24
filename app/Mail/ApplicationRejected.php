<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $companyName;
    public $driveDate;
    public $jobRole;
    public $description;

    public function __construct($studentName, $companyName, $driveDate, $jobRole, $description)
    {
        $this->studentName = $studentName;
        $this->companyName = $companyName;
        $this->driveDate = $driveDate;
        $this->jobRole = $jobRole;
        $this->description = $description;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on Your Application - ' . $this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.application_rejected',
            with: [
                'studentName' => $this->studentName,
                'companyName' => $this->companyName,
                'driveDate' => $this->driveDate,
                'jobRole' => $this->jobRole,
                'description' => $this->description,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}