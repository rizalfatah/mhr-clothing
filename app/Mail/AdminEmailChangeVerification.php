<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminEmailChangeVerification extends Mailable
{
    use Queueable, SerializesModels;

    public string $verificationCode;
    public string $userName;
    public string $verificationType; // 'old_email' or 'new_email'
    public string $newEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(string $verificationCode, string $userName, string $verificationType, string $newEmail = '')
    {
        $this->verificationCode = $verificationCode;
        $this->userName = $userName;
        $this->verificationType = $verificationType;
        $this->newEmail = $newEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->verificationType === 'old_email'
            ? 'Verify Your Current Email - MHR Admin'
            : 'Verify Your New Email - MHR Admin';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-email-verification',
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
