<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordErrorNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = (string) $request->input('email');
        $emailFingerprint = hash('sha256', strtolower(trim($email)));

        Log::info('Forgot Password Attempt', [
            'email_fingerprint' => $emailFingerprint,
            'app_env' => config('app.env'),
            'app_url' => config('app.url'),
            'mail_default' => config('mail.default'),
            'mail_from' => config('mail.from.address'),
            'has_resend_key' => (bool) (env('RESEND_KEY') || env('RESEND_API_KEY')),
        ]);

        try {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status == Password::RESET_LINK_SENT
                        ? back()->with('status', __($status))
                        : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
        } catch (\Throwable $e) {
            // Log safely for production (avoid raw email + huge traces in serverless logs)
            Log::error('Forgot Password Error', [
                'email_fingerprint' => $emailFingerprint,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'mail_default' => config('mail.default'),
                'mail_from' => config('mail.from.address'),
                'has_resend_key' => (bool) (env('RESEND_KEY') || env('RESEND_API_KEY')),
                'has_app_key' => (bool) config('app.key'),
                'app_env' => config('app.env'),
                'app_url' => config('app.url'),
            ]);

            // Best-effort: notify admin, but don't fail the request if mail is misconfigured.
            $this->sendErrorNotificationToAdmin($email, $e->getMessage(), (string) ($e->getCode() ?? 'EXCEPTION_ERROR'));

            $userMessage = 'Terjadi kesalahan saat memproses permintaan. Silakan coba lagi nanti.';

            // Give a slightly more actionable hint without leaking sensitive details.
            if ($e instanceof QueryException) {
                $userMessage = 'Sistem sedang mengalami kendala koneksi database. Silakan coba lagi nanti.';
            }

            // Common mail/config related failures.
            $lowerMessage = strtolower($e->getMessage());
            if (str_contains($lowerMessage, 'resend') || str_contains($lowerMessage, 'mail') || str_contains($lowerMessage, 'smtp')) {
                $userMessage = 'Sistem sedang mengalami kendala pengiriman email. Silakan coba lagi nanti.';
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => $userMessage]);
        }
    }

    /**
     * Send error notification to admin Gmail
     */
    private function sendErrorNotificationToAdmin($email, $errorMessage, $errorCode)
    {
        try {
            $adminEmail = env('ADMIN_EMAIL', 'admin@alwicollege.com');
            
            // Send email notification
            Mail::to($adminEmail)->send(new ForgotPasswordErrorNotification(
                $email,
                $errorMessage,
                $errorCode
            ));

            Log::info('Error notification sent to admin', [
                'admin_email' => $adminEmail,
                'user_email' => $email,
            ]);
        } catch (\Throwable $mailException) {
            // Log if mail sending fails
            Log::error('Failed to send error notification email', [
                'error' => $mailException->getMessage(),
                'user_email' => $email,
            ]);
        }
    }
}

