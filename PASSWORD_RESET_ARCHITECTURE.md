# Password Reset System - Architecture Diagram

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         USER INTERFACE                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  GET /forgot-password          GET /reset-password/{token}      │
│  ┌──────────────────────┐      ┌─────────────────────────┐      │
│  │  Forgot Password     │  →   │   Reset Password Form   │      │
│  │      Form            │      │                         │      │
│  │  Email: [_______]    │      │  Password: [_______]    │      │
│  │   [Submit]           │      │  Confirm:  [_______]    │      │
│  └──────────────────────┘      │   [Reset Password]      │      │
│                                └─────────────────────────┘      │
└─────────────────────────────────────────────────────────────────┘
          ↓                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                     REQUEST HANDLING                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  POST /forgot-password         POST /reset-password             │
│  PasswordResetLinkController   NewPasswordController            │
│   ├─ Validate email            ├─ Validate token                │
│   ├─ Check user exists         ├─ Check user exists             │
│   ├─ Generate token            ├─ Validate password             │
│   ├─ Store in DB               ├─ Hash password                 │
│   └─ Send email               └─ Update user & delete token    │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
          ↓
┌─────────────────────────────────────────────────────────────────┐
│                   PASSWORD RESET FLOW                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  1. User submits email                                           │
│     ↓                                                             │
│  2. Laravel Password::sendResetLink() called                     │
│     ├─ Generates token: Str::random(64)                         │
│     ├─ Stores in password_reset_tokens table                    │
│     └─ Fires PasswordResetLinkSent event                        │
│     ↓                                                             │
│  3. User::sendPasswordResetNotification($token) called           │
│     ├─ Builds reset URL: /reset-password/{token}               │
│     ├─ Renders email view with token                           │
│     └─ Dispatches to mail queue                                │
│     ↓                                                             │
│  4. Mail queue processes (async)                               │
│     ├─ Initializes ResendService (if MAIL_MAILER=resend)       │
│     ├─ Sends via Resend API                                    │
│     └─ Logs success/failure                                    │
│     ↓                                                             │
│  5. User receives email from Resend                            │
│     ├─ From: Alwi College <onboarding@resend.dev>             │
│     ├─ Contains: Reset button with token                       │
│     └─ Expires: 60 minutes                                      │
│     ↓                                                             │
│  6. User clicks reset link                                      │
│     ├─ Visits: /reset-password/{token}?email=...              │
│     └─ Form renders                                             │
│     ↓                                                             │
│  7. User submits new password                                   │
│     ├─ POST /reset-password with token & password              │
│     ├─ Validates password                                       │
│     ├─ Updates user password                                    │
│     ├─ Deletes token (one-time use)                            │
│     └─ Redirects to login                                       │
│     ↓                                                             │
│  8. User logs in with new password ✅                           │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## Component Interaction Diagram

```
┌──────────────────────────────────────────────────────────────────┐
│                      WEB BROWSER (User)                            │
└──────────────────────────────────────────────────────────────────┘
                              ↕ HTTP

┌──────────────────────────────────────────────────────────────────┐
│                      LARAVEL APPLICATION                           │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  Routes (routes/auth.php)                                        │
│  │                                                               │
│  ├─ GET /forgot-password                                       │
│  │  └─→ PasswordResetLinkController@create → forgot-password  │
│  │                                           .blade.php         │
│  │                                                               │
│  ├─ POST /forgot-password                                      │
│  │  └─→ PasswordResetLinkController@store                    │
│  │      ├─ Validates input                                    │
│  │      ├─ Calls Password::sendResetLink()                   │
│  │      │   ├─ Generates token                               │
│  │      │   ├─ Stores: password_reset_tokens table          │
│  │      │   └─ Calls: User::sendPasswordResetNotification() │
│  │      └─ Redirects with status message                     │
│  │                                                               │
│  ├─ GET /reset-password/{token}                               │
│  │  └─→ NewPasswordController@create → reset-password        │
│  │                                      .blade.php             │
│  │                                                               │
│  └─ POST /reset-password                                      │
│     └─→ NewPasswordController@store                           │
│         ├─ Validates password & token                         │
│         ├─ Updates: users.password                            │
│         ├─ Deletes: password_reset_tokens record            │
│         └─ Redirects to login                                 │
│                                                                    │
│  Models (app/Models/User.php)                                   │
│  │                                                               │
│  └─ sendPasswordResetNotification($token)                      │
│     ├─ Builds reset URL                                       │
│     ├─ Renders email view                                     │
│     ├─ Calls ResendService::sendEmail() [if available]       │
│     └─ Fallback: Mail::send(ResetPasswordNotification)       │
│                                                                    │
│  Services (app/Services/ResendService.php)                     │
│  │                                                               │
│  └─ sendEmail($email, $subject, $htmlBody)                    │
│     ├─ Calls Resend API client                               │
│     ├─ Handles response                                       │
│     └─ Logs success/errors                                    │
│                                                                    │
│  Mail (app/Mail/ResetPasswordNotification.php)                 │
│  │                                                               │
│  └─ Mailable class                                             │
│     ├─ envelope() → Sets subject                             │
│     ├─ content() → Points to view                            │
│     └─ attachments() → Returns empty array                   │
│                                                                    │
│  Views (resources/views/)                                       │
│  │                                                               │
│  ├─ auth/forgot-password.blade.php                           │
│  ├─ auth/reset-password.blade.php                            │
│  └─ emails/reset-password-notification.blade.php             │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘
                              ↕ SQL

┌──────────────────────────────────────────────────────────────────┐
│                      DATABASE (SQLite/MySQL)                       │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  users table                                                     │
│  ├─ id                                                          │
│  ├─ name                                                        │
│  ├─ email                                                       │
│  ├─ password (hashed)                                           │
│  └─ ...                                                          │
│                                                                    │
│  password_reset_tokens table                                    │
│  ├─ email (primary key)                                        │
│  ├─ token                                                      │
│  └─ created_at                                                 │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘
                              ↕ HTTP API

┌──────────────────────────────────────────────────────────────────┐
│                     RESEND EMAIL SERVICE                          │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  Resend API (https://api.resend.com)                           │
│  │                                                               │
│  └─ POST /emails                                               │
│     ├─ Authorization: Bearer {RESEND_API_KEY}                 │
│     ├─ from: "Alwi College <onboarding@resend.dev>"          │
│     ├─ to: {user_email}                                       │
│     ├─ subject: "Reset Password - Alwi College"              │
│     ├─ html: {rendered_email_template}                        │
│     └─ Returns: {message_id, created_at}                     │
│                                                                    │
│  Email Delivery                                                  │
│  │                                                               │
│  └─ Resend → User's Email Provider → User's Inbox            │
│     ├─ SPF/DKIM/DMARC validation                             │
│     ├─ Spam filtering                                         │
│     └─ Delivery confirmation                                  │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘
```

## Data Flow Diagram

```
              FORGOT PASSWORD REQUEST
                      ↓
┌─────────────────────────────────────────────────────────────┐
│ PasswordResetLinkController::store()                        │
│  - Input: email from request                               │
│  - Call: Password::sendResetLink(['email' => $email])     │
└─────────────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│ Laravel Password Broker                                     │
│  - Validate email exists                                   │
│  - Generate token: Str::random(64)                         │
│  - Create: password_reset_tokens record                    │
│           {email, token, created_at}                       │
│  - Trigger: User::sendPasswordResetNotification($token)   │
└─────────────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│ User::sendPasswordResetNotification($token)                 │
│  - Build: $resetUrl = config('app.url')                   │
│           . '/reset-password/' . $token                    │
│  - Get: PASSWORD_RESET_TIMEOUT from config                │
│  - Render: views/emails/reset-password-notification       │
│           with {resetUrl, userName, expiresInMinutes}    │
└─────────────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│ Check Mail Mailer Configuration                             │
│                                                              │
│ IF MAIL_MAILER === 'resend'                               │
│ ├─ Use ResendService::sendEmail()                         │
│ │   - Call Resend API with email & HTML                  │
│ │   - Handle response & log                              │
│ └─ SUCCESS: Email sent via Resend ✓                      │
│                                                              │
│ ELSE                                                        │
│ ├─ Use Mail::send(ResetPasswordNotification::class)      │
│ └─ SUCCESS: Email sent via configured mailer ✓           │
│                                                              │
│ IF EXCEPTION                                               │
│ ├─ Log error with full context                           │
│ └─ Return user-friendly error message                    │
└─────────────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│ EMAIL DISPATCH                                              │
│  - Via ResendService (if Resend enabled)                  │
│    ├─ Resend API Client initializes                       │
│    ├─ Email object constructed                            │
│    ├─ API request sent                                    │
│    ├─ Response received & logged                          │
│    └─ Returns success/failure                             │
│                                                              │
│  - Via Mail Queue (standard Laravel)                       │
│    ├─ Mailable queued                                     │
│    ├─ Queue processor picks up job                        │
│    ├─ Mail facade sends                                   │
│    └─ Logged & reported                                   │
└─────────────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│ USER RECEIVES EMAIL                                         │
│  - From: Alwi College <onboarding@resend.dev>             │
│  - To: user@example.com                                    │
│  - Subject: Reset Password - Alwi College                 │
│  - Body: HTML with reset button                            │
│  - Reset link: /reset-password/{token}?email=...          │
│  - Expires: 60 minutes from generation                     │
└─────────────────────────────────────────────────────────────┘
                      ↓
              USER CLICKS RESET LINK
                      ↓
┌─────────────────────────────────────────────────────────────┐
│ NewPasswordController::store()                              │
│  - Input: password, token, email                           │
│  - Call: Password::reset([...], callback)                 │
│    ├─ Validate token                                      │
│    ├─ Validate email & password                           │
│    ├─ Call callback                                       │
│    │  └─ Update user password                             │
│    ├─ Delete token from DB                                │
│    └─ Fire PasswordReset event                            │
│  - Redirect: to login with success message                │
└─────────────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│ DATABASE UPDATES                                            │
│  - UPDATE users                                            │
│    SET password = bcrypt(new_password)                     │
│    WHERE id = user_id                                      │
│                                                              │
│  - DELETE FROM password_reset_tokens                       │
│    WHERE email = user_email                                │
└─────────────────────────────────────────────────────────────┘
                      ↓
              PASSWORD RESET COMPLETE ✅
                      ↓
              USER LOGS IN SUCCESSFULLY
```

## Technology Stack

```
┌──────────────────────────────────────────────────────────────┐
│                    TECHNOLOGY LAYERS                          │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  Frontend Layer                                              │
│  ├─ Blade Templates (HTML forms)                           │
│  ├─ CSS (form styling)                                     │
│  ├─ JavaScript (form validation)                           │
│  └─ HTTP (form submission)                                 │
│                                                               │
│  Application Layer                                           │
│  ├─ Laravel Framework (routing, validation, auth)         │
│  ├─ Controllers (request handling)                         │
│  ├─ Models (user management)                               │
│  ├─ Services (email dispatch)                              │
│  └─ Events (notifications)                                 │
│                                                               │
│  Email Layer                                                 │
│  ├─ Mailable classes (email composition)                  │
│  ├─ Blade views (email templates)                          │
│  ├─ Mail facade (email abstraction)                        │
│  ├─ Resend service (email delivery)                        │
│  └─ Queue system (async dispatch)                          │
│                                                               │
│  Data Layer                                                  │
│  ├─ SQLite/MySQL (persistent storage)                     │
│  ├─ users table (user accounts)                           │
│  ├─ password_reset_tokens table (reset tokens)           │
│  └─ Migrations (schema management)                        │
│                                                               │
│  External Services                                          │
│  ├─ Resend API (email delivery provider)                  │
│  └─ SMTP/HTTP (email transport)                           │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

## Configuration Flow

```
┌──────────────────────────────────────────────────────────────┐
│              ENVIRONMENT VARIABLES (.env)                     │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  MAIL_MAILER=resend                                         │
│  RESEND_API_KEY=re_xxxxxxxxx                                │
│  MAIL_FROM_ADDRESS=onboarding@resend.dev                   │
│  MAIL_FROM_NAME="Alwi College"                             │
│  APP_URL=http://localhost:8000                             │
│                                                               │
└──────────────────────────────────────────────────────────────┘
           ↓ (loaded by Laravel)
┌──────────────────────────────────────────────────────────────┐
│            CONFIGURATION FILES (config/)                      │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  config/mail.php                                            │
│  ├─ default = 'resend' (if RESEND_API_KEY set)            │
│  ├─ mailers.resend                                         │
│  │  ├─ transport = 'resend'                                │
│  │  └─ from = 'Alwi College <onboarding@resend.dev>'     │
│  └─ from.address = 'onboarding@resend.dev'               │
│                                                               │
│  config/auth.php                                            │
│  ├─ passwords.users.expire = 60 (minutes)                 │
│  └─ passwords.users.throttle = 60 (seconds)              │
│                                                               │
│  config/app.php                                             │
│  ├─ url = 'http://localhost:8000'                         │
│  └─ name = 'Alwi College'                                 │
│                                                               │
└──────────────────────────────────────────────────────────────┘
           ↓ (used by application)
┌──────────────────────────────────────────────────────────────┐
│          APPLICATION USAGE (during password reset)            │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  User::sendPasswordResetNotification()                       │
│  ├─ Gets: config('app.url') → build reset URL            │
│  ├─ Gets: config('auth.passwords.users.expire')          │
│  ├─ Gets: config('mail.default') → check if 'resend'    │
│  ├─ Gets: config('mail.from.address')                    │
│  ├─ Gets: config('mail.from.name')                       │
│  └─ Uses: ResendService if 'resend', else Mail facade   │
│                                                               │
│  ResendService::sendEmail()                                 │
│  ├─ Gets: env('RESEND_API_KEY')                          │
│  ├─ Initializes: Resend client                           │
│  └─ Sends: Email via Resend API                          │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

---

**Architecture Diagram Generated**: January 10, 2026
