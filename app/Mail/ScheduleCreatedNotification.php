<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientName;
    public $recipientEmail;
    public $scheduleInfo;
    public $userType; // 'guru' atau 'siswa'
    public $appName;
    public $appUrl;

    /**
     * Create a new message instance.
     * 
     * $scheduleInfo should contain:
     * - date (tanggal jadwal)
     * - subject_name
     * - teacher_name
     * - class_name
     * - school_name
     * - start_time
     * - end_time
     */
    public function __construct($recipientName, $recipientEmail, $scheduleInfo, $userType = 'siswa')
    {
        $this->recipientName = $recipientName;
        $this->recipientEmail = $recipientEmail;
        $this->scheduleInfo = $scheduleInfo;
        $this->userType = $userType;
        $this->appName = config('app.name', 'Alwi College');
        $this->appUrl = config('app.url', 'http://localhost:8000');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $date = \Carbon\Carbon::parse($this->scheduleInfo['date'])->format('d M Y');
        $subject = $this->userType === 'guru' 
            ? "[JADWAL BARU] Anda dijadwalkan mengajar - {$date}"
            : "[JADWAL BARU] Jadwal {$this->scheduleInfo['school_name']} - {$date}";

        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.schedule-created-notification',
            with: [
                'recipientName' => $this->recipientName,
                'scheduleInfo' => $this->scheduleInfo,
                'userType' => $this->userType,
                'appName' => $this->appName,
                'appUrl' => $this->appUrl,
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
