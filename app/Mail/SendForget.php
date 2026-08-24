<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendForget extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $name, $email)
    {
        $this->otp = $otp;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset OTP',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.password-reset-otp', // Blade template for OTP email
            with: [
                'otp' => $this->otp,
                'name' => $this->name,
                'email' => $this->email,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
