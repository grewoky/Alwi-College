<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordErrorNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
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
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode() ?? 'EXCEPTION_ERROR';
            $email = $request->email;

            // Log the error for system debugging
            Log::error('Forgot Password Error', [
                'email' => $email,
                'error_message' => $errorMessage,
                'error_code' => $errorCode,
                'trace' => $e->getTraceAsString(),
            ]);

            // Send error notification to admin email
            $this->sendErrorNotificationToAdmin($email, $errorMessage, $errorCode);

            // Show user-friendly error message
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Terjadi kesalahan saat memproses permintaan. Tim teknis telah diberitahu. Silakan coba lagi nanti.']);
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

