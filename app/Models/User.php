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
     * Send custom mailable via proper Mailable pattern.
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

        try {
            // Send via proper Mailable pattern (works with Resend driver)
            Mail::to($this->email)->send(new ResetPasswordNotification(
                resetUrl: $resetUrl,
                userName: $this->name,
                expiresInMinutes: $expiresInMinutes
            ));

            \Illuminate\Support\Facades\Log::info('Password reset email sent successfully', [
                'user_id' => $this->id,
                'email' => $this->email,
            ]);
        } catch (\Throwable $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Failed to send password reset email', [
                'user_id' => $this->id,
                'email' => $this->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
