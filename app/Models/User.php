<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// WAJIB
use Spatie\Permission\Traits\HasRoles;
use App\Services\ResendService;

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
     * Send via direct Resend API call using ResendService.
     *
     * @param string $token The password reset token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        // Build the reset URL with token
        $url = url('/reset-password/' . $token . '?email=' . urlencode($this->email));

        // Create HTML email body
        $html = "
            <h2>Reset Password</h2>
            <p>Halo " . htmlspecialchars($this->name) . ",</p>
            <p>Kami menerima permintaan untuk me-reset password Anda. Klik link di bawah untuk melanjutkan:</p>
            <p>
                <a href='{$url}' style='display: inline-block; padding: 10px 20px; background-color: #667eea; color: white; text-decoration: none; border-radius: 5px;'>
                    Reset Password
                </a>
            </p>
            <p>Atau copy-paste link ini ke browser:</p>
            <p><code>{$url}</code></p>
            <p>Link berlaku selama 60 menit.</p>
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <p>Terima kasih,<br/>Tim Alwi College</p>
        ";

        try {
            // Direct call to Resend API via ResendService
            app(ResendService::class)->sendCustomEmail(
                $this->email,
                'Reset Password - Alwi College',
                $html
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send password reset email', [
                'user_id' => $this->id,
                'email' => $this->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
