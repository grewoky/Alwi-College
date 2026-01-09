<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $userPassword;
    public $userRole;
    public $appName;
    public $appUrl;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userEmail, $userPassword, $userRole = 'siswa')
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
        $this->userRole = $userRole;
        $this->appName = config('app.name', 'Alwi College');
        $this->appUrl = config('app.url', 'http://localhost:8000');
        $this->loginUrl = $this->appUrl . '/login';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: '[AKUN BARU] ' . $this->userName . ' - ' . $this->appName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-created-notification',
            with: [
                'userName' => $this->userName,
                'userEmail' => $this->userEmail,
                'userPassword' => $this->userPassword,
                'userRole' => $this->userRole,
                'appName' => $this->appName,
                'appUrl' => $this->appUrl,
                'loginUrl' => $this->loginUrl,
            ],
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
