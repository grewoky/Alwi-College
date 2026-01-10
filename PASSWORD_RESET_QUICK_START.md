# Password Reset Setup - Quick Reference Checklist

## 📋 QUICK START (5 Steps)

### ✅ Step 1: Install Resend Package

```bash
composer require resend/laravel
```

### ✅ Step 2: Update .env File

```dotenv
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=onboarding@resend.dev
MAIL_FROM_NAME="Alwi College"
RESEND_API_KEY=re_xxxxxxxxx
```

### ✅ Step 3: Get Resend API Key

1. Go to https://resend.com/api-keys
2. Click "Create API Key"
3. Copy key and paste in `.env` as `RESEND_API_KEY=re_xxxx`

### ✅ Step 4: Deploy to Vercel

1. Push changes to GitHub
2. Set Vercel environment variables (same as .env above)
3. Vercel auto-deploys

### ✅ Step 5: Test

1. Visit `/forgot-password`
2. Enter test email
3. Check email received from Resend
4. Click reset link and set new password

---

## 📝 WHAT WAS IMPLEMENTED

### Created Files

-   ✅ `app/Mail/ResetPasswordNotification.php` - Custom password reset email
-   ✅ `resources/views/emails/reset-password-notification.blade.php` - Email HTML template

### Modified Files

-   ✅ `app/Models/User.php` - Override password reset notification
-   ✅ `.env.example` - Added Resend configuration

### Already Configured (No Changes Needed)

-   ✅ `database/migrations/0001_01_01_000000_create_users_table.php` - password_reset_tokens table
-   ✅ `config/mail.php` - Resend transport configuration
-   ✅ `routes/auth.php` - Password reset routes
-   ✅ `app/Http/Controllers/Auth/PasswordResetLinkController.php` - Forgot password handler
-   ✅ `app/Http/Controllers/Auth/NewPasswordController.php` - Password reset handler

---

## 🔒 PASSWORD RESET FLOW

```
1. User clicks "Forgot Password"
   ↓
2. Enters email → POST /forgot-password
   ↓
3. Laravel generates token & stores in password_reset_tokens table
   ↓
4. User::sendPasswordResetNotification($token) called
   ↓
5. Custom mailable sent via Resend with reset link
   ↓
6. User receives email with reset button
   ↓
7. User clicks button → GET /reset-password/{token}
   ↓
8. User enters new password → POST /reset-password
   ↓
9. Token deleted, password updated
   ↓
10. User logs in with new password ✅
```

---

## 🚀 ENVIRONMENT VARIABLES

### Minimum Required

```
MAIL_MAILER=resend
RESEND_API_KEY=re_xxxxxxxxx
MAIL_FROM_ADDRESS=onboarding@resend.dev
MAIL_FROM_NAME="Alwi College"
```

### Optional

```
ADMIN_EMAIL=admin@alwicollege.com
PASSWORD_RESET_TIMEOUT=60
```

---

## ✔️ VERIFICATION

### Check installed

```bash
composer show resend/laravel
```

### Check configuration

```bash
php artisan config:show mail.mailers.resend
```

### Check routes

```bash
php artisan route:list --name=password
```

### Check table

```bash
php artisan tinker
>>> DB::table('password_reset_tokens')->get()
```

---

## 🐛 TROUBLESHOOTING

| Issue             | Solution                              |
| ----------------- | ------------------------------------- |
| Email not sending | Check RESEND_API_KEY is valid         |
| Wrong sender name | Check MAIL_FROM_NAME is set           |
| Token not working | Check it hasn't expired (60 mins)     |
| Database error    | Run `php artisan migrate`             |
| Route not found   | Routes already configured in auth.php |

---

## 📚 DOCUMENTATION FILES

-   **Full Setup Guide**: `PASSWORD_RESET_SETUP.md`
-   **Implementation Details**: `PASSWORD_RESET_IMPLEMENTATION_COMPLETE.md`
-   **This Quick Reference**: `PASSWORD_RESET_QUICK_START.md`

---

## 🎯 WHAT HAPPENS WHEN USER RESETS PASSWORD

### Email Content Includes:

-   ✅ User's name greeting
-   ✅ Reset button with 60-minute expiration notice
-   ✅ Alternative link (copy-paste option)
-   ✅ Step-by-step instructions (in Indonesian)
-   ✅ Security note about email safety
-   ✅ Admin contact info for support
-   ✅ Professional branding with Alwi College logo colors

### Security Features:

-   ✅ Tokens expire after 60 minutes
-   ✅ Tokens are one-time use only
-   ✅ Tokens are 64-character random strings
-   ✅ Email verification required
-   ✅ HTTPS only in production
-   ✅ Secure token generation

---

## 💡 BEST PRACTICES IMPLEMENTED

1. **Error Handling**: Try-catch blocks with fallback
2. **Logging**: All errors logged for debugging
3. **User Feedback**: Clear success/error messages
4. **Email Template**: Professional HTML with branding
5. **Responsive Design**: Works on mobile and desktop
6. **Accessibility**: Proper heading hierarchy
7. **Security**: Token expiration and one-time use

---

## 📊 SUMMARY

| Item           | Status     | Note                              |
| -------------- | ---------- | --------------------------------- |
| Database Table | ✅ Ready   | Already exists                    |
| Package        | ⏳ Install | `composer require resend/laravel` |
| Configuration  | ✅ Ready   | In .env.example                   |
| Custom Email   | ✅ Ready   | Created & styled                  |
| User Model     | ✅ Ready   | Overridden                        |
| Routes         | ✅ Ready   | Already configured                |
| Controllers    | ✅ Ready   | Already configured                |

---

**Next Action**: Run `composer require resend/laravel` and set environment variables!
