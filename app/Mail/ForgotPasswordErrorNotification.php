<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $errorMessage;
    public $errorCode;
    public $timestamp;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $errorMessage, $errorCode = null)
    {
        $this->email = $email;
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode ?? 'UNKNOWN_ERROR';
        $this->timestamp = now()->format('d-m-Y H:i:s');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[ERROR] Notifikasi Error - Lupa Password - ' . env('APP_NAME'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.forgot-password-error',
            with: [
                'email' => $this->email,
                'errorMessage' => $this->errorMessage,
                'errorCode' => $this->errorCode,
                'timestamp' => $this->timestamp,
                'appName' => env('APP_NAME'),
                'appUrl' => env('APP_URL'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
