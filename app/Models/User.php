<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// WAJIB
use Spatie\Permission\Traits\HasRoles;
use App\Mail\ResetPasswordNotification;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name','email','password','is_approved','is_active','phone'];
    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Override the default password reset notification.
     * Send custom mailable via Resend instead of using default notification.
     *
     * @param string $token The password reset token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        // Build the reset URL with token
        $resetUrl = config('app.url') . '/reset-password/' . $token . '?email=' . urlencode($this->email);

        // Get password reset timeout in minutes (default: 60)
        $expiresInMinutes = config('auth.passwords.users.expire', 60);

        // Send the custom mailable via ResendService if available, otherwise via Mail facade
        try {
            // Try using ResendService if it's available and mail mailer is set to resend
            if (config('mail.default') === 'resend') {
                $htmlBody = view('emails.reset-password-notification', [
                    'resetUrl' => $resetUrl,
                    'userName' => $this->name,
                    'expiresInMinutes' => $expiresInMinutes,
                ])->render();

                app(\App\Services\ResendService::class)->sendEmail(
                    $this->email,
                    'Reset Password - Alwi College',
                    $htmlBody
                );
            } else {
                // Fallback to standard Mail facade if not using Resend
                Mail::send(new ResetPasswordNotification(
                    $resetUrl,
                    $this->name,
                    $expiresInMinutes
                ));
            }
        } catch (\Throwable $e) {
            // If anything goes wrong, log it and use Mail facade
            \Illuminate\Support\Facades\Log::warning('Failed to send password reset via ResendService', [
                'user_id' => $this->id,
                'email' => $this->email,
                'error' => $e->getMessage(),
            ]);

            // Fallback
            Mail::send(new ResetPasswordNotification(
                $resetUrl,
                $this->name,
                $expiresInMinutes
            ));
        }
    }
}
