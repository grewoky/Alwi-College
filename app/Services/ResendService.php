<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ScheduleCreatedNotification;
use Throwable;

class ResendService
{
    /**
     * Send schedule notification email to teacher or student
     * 
     * @param string $recipientEmail
     * @param string $recipientName
     * @param array $scheduleInfo - ['date', 'subject_name', 'teacher_name', 'class_name', 'school_name', 'start_time', 'end_time']
     * @param string $userType - 'guru' or 'siswa'
     * @return bool
     */
    public function sendScheduleNotification(
        string $recipientEmail,
        string $recipientName,
        array $scheduleInfo,
        string $userType = 'siswa'
    ): bool {
        try {
            Mail::to($recipientEmail)->send(new ScheduleCreatedNotification(
                $recipientName,
                $recipientEmail,
                $scheduleInfo,
                $userType
            ));

            Log::info('Schedule notification sent', [
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'user_type' => $userType,
            ]);

            return true;
        } catch (Throwable $e) {
            Log::warning('Failed to send schedule notification', [
                'recipient_email' => $recipientEmail,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send custom HTML email (simple method)
     * 
     * @param string $recipientEmail
     * @param string $subject
     * @param string $htmlBody
     * @return bool
     */
    public function sendEmail(
        string $recipientEmail,
        string $subject,
        string $htmlBody
    ): bool {
        return $this->sendCustomEmail($recipientEmail, $subject, $htmlBody);
    }

    /**
     * Send custom HTML email
     * Uses the ResetPasswordNotification mailable to send HTML emails
     * 
     * @param string $recipientEmail
     * @param string $subject
     * @param string $htmlBody
     * @return bool
     */
    public function sendCustomEmail(
        string $recipientEmail,
        string $subject,
        string $htmlBody
    ): bool {
        try {
            // Create a temporary mailable from raw HTML
            // For now, using mail directly via Mail facade with proper config
            Mail::raw($htmlBody, function ($message) use ($recipientEmail, $subject) {
                $message->to($recipientEmail)
                        ->subject($subject)
                        ->getSwiftMessage()
                        ->getHeaders()
                        ->addTextHeader('Content-Type', 'text/html; charset=UTF-8');
            });

            Log::info('Custom email sent', [
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
            ]);

            return true;
        } catch (Throwable $e) {
            Log::warning('Failed to send custom email', [
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send account creation notification
     * 
     * @param string $recipientEmail
     * @param string $recipientName
     * @param string $password
     * @param string $userType
     * @return bool
     */
    public function sendAccountCreationEmail(
        string $recipientEmail,
        string $recipientName,
        string $password,
        string $userType = 'siswa'
    ): bool {
        try {
            $mailClass = \App\Mail\AccountCreatedNotification::class;
            Mail::to($recipientEmail)->send(new $mailClass(
                $recipientName,
                $recipientEmail,
                $password,
                $userType
            ));

            Log::info('Account creation email sent', [
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'user_type' => $userType,
            ]);

            return true;
        } catch (Throwable $e) {
            Log::warning('Failed to send account creation email', [
                'recipient_email' => $recipientEmail,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
