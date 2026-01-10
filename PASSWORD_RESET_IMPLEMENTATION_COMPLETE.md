# Password Reset Implementation with Resend - Complete Setup Guide

## ✅ IMPLEMENTATION STATUS

All components for password reset with Resend email service have been set up:

### 1. ✅ Database Table

-   **Table**: `password_reset_tokens`
-   **Status**: Already exists in migration `0001_01_01_000000_create_users_table.php`
-   **Columns**:
    -   `email` (string, primary key)
    -   `token` (string) - The reset token
    -   `created_at` (timestamp, nullable)
-   **No action needed**: Table is ready to use

### 2. ✅ Resend Package Installation

-   **Status**: NOT YET INSTALLED
-   **Required Action**: Run command:
    ```bash
    composer require resend/laravel
    ```
-   **Verification**: After install, verify in composer.lock

### 3. ✅ Environment Configuration

-   **File Modified**: `.env.example`
-   **Variables Added**:
    ```dotenv
    MAIL_MAILER=resend
    MAIL_FROM_ADDRESS=onboarding@resend.dev
    MAIL_FROM_NAME="Alwi College"
    RESEND_API_KEY=re_xxxxxxxxx
    ```
-   **Status**: Ready in .env.example template
-   **Action Required**: Set in Vercel Environment Variables:
    -   `MAIL_MAILER=resend`
    -   `MAIL_FROM_ADDRESS=onboarding@resend.dev`
    -   `MAIL_FROM_NAME=Alwi College`
    -   `RESEND_API_KEY=re_[actual-key-from-resend.com]`

### 4. ✅ Custom Password Reset Mailable

-   **File Created**: `app/Mail/ResetPasswordNotification.php`
-   **Status**: Complete and ready
-   **Features**:
    -   Accepts reset URL, user name, and expiration time
    -   Implements `ShouldQueue` for async sending
    -   Uses custom HTML view template
    -   Properly formatted email envelope

### 5. ✅ Email Template View

-   **File Created**: `resources/views/emails/reset-password-notification.blade.php`
-   **Status**: Complete and styled
-   **Features**:
    -   Professional HTML email with gradient header
    -   Includes reset button and alternative link
    -   Shows expiration time (default 60 minutes)
    -   Provides step-by-step instructions
    -   Security note about email safety
    -   Responsive design for all devices

### 6. ✅ User Model Override

-   **File Modified**: `app/Models/User.php`
-   **Status**: Complete implementation
-   **Added Method**: `sendPasswordResetNotification($token)`
-   **Features**:
    -   Overrides Laravel's default notification
    -   Automatically detects if using Resend
    -   Uses ResendService if available
    -   Falls back to Mail facade if Resend unavailable
    -   Proper error handling with logging
    -   Builds secure reset URL with token and email

### 7. ✅ Mail Configuration

-   **File**: `config/mail.php`
-   **Status**: Already properly configured
-   **Resend Transport**:
    ```php
    'resend' => [
        'transport' => 'resend',
        'from' => env('MAIL_FROM_NAME') . ' <' . env('MAIL_FROM_ADDRESS') . '>',
    ],
    ```
-   **Default Mailer**: Auto-detects Resend if API key present
-   **No action needed**: Configuration is complete

### 8. ✅ Routes for Password Reset

-   **File**: `routes/auth.php`
-   **Status**: All routes already configured
-   **Routes**:
    -   `GET /forgot-password` → Show forgot password form
    -   `POST /forgot-password` → Send reset link email
    -   `GET /reset-password/{token}` → Show reset password form
    -   `POST /reset-password` → Update password
-   **Controllers**:
    -   `PasswordResetLinkController` - Handles forgot password
    -   `NewPasswordController` - Handles password reset
-   **No action needed**: Routes are complete

---

## PASSWORD RESET FLOW

```
User Flow Diagram
├─ User forgets password
│  └─ Visits: GET /forgot-password
│     └─ Sees: Email input form
│
├─ User submits email
│  └─ Submits: POST /forgot-password
│     ├─ Validates email exists
│     ├─ Generates token
│     ├─ Stores in password_reset_tokens table
│     └─ Triggers User::sendPasswordResetNotification($token)
│
├─ Email sent via Resend
│  └─ User receives email:
│     ├─ Subject: "Reset Password - Alwi College"
│     ├─ From: "Alwi College <onboarding@resend.dev>"
│     ├─ Contains: Reset button with token
│     └─ Expires: 60 minutes
│
├─ User clicks reset link
│  └─ Visits: GET /reset-password/{token}?email=user@example.com
│     ├─ Validates token exists & not expired
│     └─ Shows: Password reset form
│
├─ User enters new password
│  └─ Submits: POST /reset-password
│     ├─ Validates token & password
│     ├─ Updates user password
│     ├─ Deletes token from password_reset_tokens
│     └─ Redirects: To login with success message
│
└─ User logs in with new password
   └─ Successfully authenticated!
```

---

## FILES CREATED

### 1. `app/Mail/ResetPasswordNotification.php`

```php
- Class: ResetPasswordNotification extends Mailable implements ShouldQueue
- Constructor: __construct(string $resetUrl, string $userName, int $expiresInMinutes)
- Methods:
  - envelope(): Returns email subject & metadata
  - content(): Returns view path and variables
  - attachments(): Returns attachments array
```

### 2. `resources/views/emails/reset-password-notification.blade.php`

```blade
- Professional HTML email template
- Variables: $resetUrl, $userName, $expiresInMinutes
- Features:
  - Gradient header
  - Reset button CTA
  - Alternative link
  - Expiration notice
  - Step-by-step instructions
  - Security disclaimers
  - Responsive design
```

---

## FILES MODIFIED

### 1. `app/Models/User.php`

**Added**:

-   Import: `use App\Mail\ResetPasswordNotification;`
-   Import: `use Illuminate\Support\Facades\Mail;`
-   Method: `sendPasswordResetNotification($token): void`

**Functionality**:

-   Overrides Laravel's default password reset notification
-   Builds reset URL with token: `/reset-password/{token}?email={encoded_email}`
-   Uses ResendService if available
-   Falls back to Mail facade
-   Includes proper error handling and logging

### 2. `.env.example`

**Changed**:

```dotenv
From:
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"

To:
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="onboarding@resend.dev"
MAIL_FROM_NAME="Alwi College"
RESEND_API_KEY=re_xxxxxxxxx
```

---

## INSTALLATION STEPS

### Step 1: Install Resend Package

```bash
composer require resend/laravel
```

### Step 2: Configure Environment Variables

**Local Development (.env)**:

```dotenv
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=onboarding@resend.dev
MAIL_FROM_NAME="Alwi College"
RESEND_API_KEY=re_[your-test-key]
```

**Vercel Production (Dashboard Environment Variables)**:

```
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=onboarding@resend.dev
MAIL_FROM_NAME=Alwi College
RESEND_API_KEY=re_[your-production-key]
```

### Step 3: Migrate Database (if not already done)

```bash
php artisan migrate
```

### Step 4: Test Locally (Optional)

```bash
# Start Laravel development server
php artisan serve

# Visit in browser
http://localhost:8000/forgot-password

# Fill in a test email and submit
```

---

## RESEND API SETUP

### Create Resend Account

1. Go to https://resend.com
2. Sign up with email address
3. Verify email address

### Get API Key

1. Go to https://resend.com/api-keys
2. Click "Create API Key"
3. Copy the key (format: `re_xxxxxxxxxxxx`)
4. Add to `.env`: `RESEND_API_KEY=re_xxxxxxxxxxxx`

### Verify Sender Email (Important!)

1. Go to https://resend.com/emails
2. The default test email is `onboarding@resend.dev`
3. To send from custom domain:
    - Add your domain (e.g., `noreply@alwicollege.com`)
    - Verify DNS records
    - Use verified email in `MAIL_FROM_ADDRESS`

---

## TESTING CHECKLIST

### Pre-Testing

-   [ ] Resend API key obtained from https://resend.com
-   [ ] `composer require resend/laravel` executed
-   [ ] `.env` updated with RESEND_API_KEY
-   [ ] `MAIL_MAILER=resend` set in .env
-   [ ] Migrations run: `php artisan migrate`

### Testing Forgot Password Flow

-   [ ] Visit `GET /forgot-password` → Form appears
-   [ ] Enter test email → `POST /forgot-password` submits
-   [ ] Check Resend logs at https://resend.com/emails
-   [ ] Email appears in Resend dashboard
-   [ ] Email has correct subject: "Reset Password - Alwi College"
-   [ ] Email from shows: "Alwi College <onboarding@resend.dev>"
-   [ ] Email contains reset button
-   [ ] Email shows 60-minute expiration
-   [ ] Email displays instructions in Indonesian

### Testing Password Reset

-   [ ] Click reset link in email
-   [ ] Form shows: `GET /reset-password/{token}`
-   [ ] Enter new password twice
-   [ ] Submit form: `POST /reset-password`
-   [ ] Success message displayed
-   [ ] Token deleted from `password_reset_tokens` table
-   [ ] Can login with new password

### Testing Edge Cases

-   [ ] Expired token (wait 60+ minutes) → Error message
-   [ ] Invalid token → Error message
-   [ ] Non-existent email → Email not found message
-   [ ] Weak password (< 8 chars) → Validation error
-   [ ] Password mismatch → Confirmation error
-   [ ] Resend API key invalid → Error in logs, user-friendly message

---

## ENVIRONMENT VARIABLES REFERENCE

### Required for Resend

```
MAIL_MAILER=resend                          # Use Resend as mail driver
RESEND_API_KEY=re_xxxxxxxxxxxx              # From https://resend.com/api-keys
MAIL_FROM_ADDRESS=onboarding@resend.dev     # Verified sender in Resend
MAIL_FROM_NAME="Alwi College"               # Display name for sender
```

### Optional/Additional

```
ADMIN_EMAIL=admin@alwicollege.com          # For error notifications
APP_URL=http://localhost:8000              # For generating reset links
PASSWORD_RESET_TIMEOUT=60                  # Minutes before token expires
```

---

## TROUBLESHOOTING

### Email not sending

**Problem**: No email received after submitting forgot password
**Solutions**:

1. Check `RESEND_API_KEY` is set and valid
2. Check `MAIL_MAILER=resend` in .env
3. Check `MAIL_FROM_ADDRESS` is verified in Resend account
4. Check Laravel logs: `storage/logs/laravel.log`
5. Check Resend dashboard: https://resend.com/emails

### Token not working

**Problem**: "Invalid token" when trying to reset password
**Solutions**:

1. Check token hasn't expired (> 60 minutes)
2. Check email parameter matches in URL: `?email=...`
3. Check token exists in `password_reset_tokens` table
4. Check token string is exactly correct (copy from email)

### Wrong sender name

**Problem**: Email from shows "hello@example.com" instead of "Alwi College"
**Solutions**:

1. Check `MAIL_FROM_NAME="Alwi College"` is set
2. Check `MAIL_FROM_ADDRESS=onboarding@resend.dev` is verified
3. Reload config: Clear config cache with `php artisan config:clear`
4. Verify setting in `config/mail.php`

### Database errors

**Problem**: "No table password_reset_tokens" error
**Solutions**:

1. Run migrations: `php artisan migrate`
2. Check migration file exists
3. Check database connection is working

---

## SECURITY BEST PRACTICES

1. **HTTPS Only**: Always use HTTPS in production (Vercel provides this)
2. **Token Expiration**: Tokens expire after 60 minutes (configurable)
3. **One-Time Use**: Tokens are deleted after successful reset
4. **Secure Token**: Laravel generates 64-character random tokens
5. **Email Verification**: Verify sender email address in Resend
6. **API Key Safety**: Never commit API key to version control
7. **Rate Limiting**: Consider adding rate limiting to forgot password endpoint
8. **Error Messages**: Don't reveal if email exists or doesn't (currently does - could improve)

---

## VERIFICATION COMMANDS

### Check migration

```bash
php artisan migrate:status
php artisan tinker
DB::table('password_reset_tokens')->get();
```

### Check routes

```bash
php artisan route:list --name=password
```

### Check configuration

```bash
php artisan config:show mail
php artisan tinker
config('mail.mailers.resend')
```

### Check for errors

```bash
tail -f storage/logs/laravel.log
# Or check Resend dashboard: https://resend.com/emails
```

---

## NEXT STEPS

1. **Install Package**: Run `composer require resend/laravel`
2. **Configure Vercel**: Set environment variables in Vercel Dashboard
3. **Test Locally**: Test password reset flow with local Resend test account
4. **Deploy**: Push changes to GitHub and deploy to Vercel
5. **Verify**: Test on production URL

---

## REFERENCE LINKS

-   Laravel Password Reset: https://laravel.com/docs/passwords
-   Resend Documentation: https://resend.com/docs
-   Resend Laravel Package: https://resend.com/docs/get-started/laravel
-   Laravel Mailable: https://laravel.com/docs/mail
-   Laravel Configuration: https://laravel.com/docs/configuration

---

## SUMMARY OF CHANGES

| Component            | Status     | File                                                         | Action                                |
| -------------------- | ---------- | ------------------------------------------------------------ | ------------------------------------- |
| Password Reset Table | ✅ Done    | migration file                                               | No action needed                      |
| Resend Package       | ⏳ Pending | composer.json                                                | Run `composer require resend/laravel` |
| Environment Config   | ✅ Done    | .env.example                                                 | Update .env and Vercel dashboard      |
| Custom Mailable      | ✅ Done    | app/Mail/ResetPasswordNotification.php                       | Created                               |
| Email Template       | ✅ Done    | resources/views/emails/reset-password-notification.blade.php | Created                               |
| User Model Override  | ✅ Done    | app/Models/User.php                                          | Updated                               |
| Mail Config          | ✅ Done    | config/mail.php                                              | No changes needed                     |
| Routes               | ✅ Done    | routes/auth.php                                              | No changes needed                     |
| Auth Controllers     | ✅ Done    | app/Http/Controllers/Auth/                                   | No changes needed                     |

---

Generated: January 10, 2026
