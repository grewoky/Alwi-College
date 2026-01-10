<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $userEmail;
    public string $password;
    public string $userType; // 'siswa' or 'guru'

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $userName,
        string $userEmail,
        string $password,
        string $userType = 'siswa'
    ) {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->password = $password;
        $this->userType = $userType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->userType === 'guru' ? 'Akun Guru Dibuat' : 'Akun Siswa Dibuat';
        
        return new Envelope(
            subject: $subject . ' - Alwi College',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-creation',
            with: [
                'userName' => $this->userName,
                'userEmail' => $this->userEmail,
                'password' => $this->password,
                'userType' => $this->userType,
                'userTypeLabel' => $this->userType === 'guru' ? 'Guru' : 'Siswa',
            ],
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
