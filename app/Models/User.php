<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Log;
// WAJIB
use Spatie\Permission\Traits\HasRoles;

use Throwable;

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
     * Use Laravel's mail notification so the configured mailer (SMTP/Resend/etc.) is respected.
     *
     * @param string $token The password reset token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        try {
            $this->notify(new ResetPassword($token));

            Log::info('Password reset email dispatched', [
                'user_id' => $this->id,
                'mail_default' => config('mail.default'),
                'mail_from' => config('mail.from.address'),
            ]);
        } catch (Throwable $e) {
            Log::error('Password reset email failed to send', [
                'user_id' => $this->id,
                'mail_default' => config('mail.default'),
                'mail_from' => config('mail.from.address'),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            // Bubble up so the controller can show an error (avoid false success).
            throw $e;
        }
    }
}
