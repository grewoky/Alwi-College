# EMAIL TESTING GUIDE - Password Reset & Account Creation

## ✅ What Was Just Fixed

### Problem
Both **Password Reset** and **Account Creation** emails were failing silently:
- ❌ "Email failed to send" (account creation)
- ❌ No notification received (password reset)

### Root Cause
Both were using **ResendService with Mail::raw()** which doesn't work with Resend driver

### Solution
Both now use **proper Mailable pattern** (what Laravel recommends):
```php
Mail::to($email)->send(new MyMailable(...));
```

---

## 🧪 Test 1: Account Creation Email

### How to Test
1. Open browser → https://alwi-college.vercel.app/admin
2. Login with admin account
3. Click **"Add Student"** or **"Add Teacher"**
4. Fill form:
   ```
   Name:    Test User 001
   Email:   your-test-email@gmail.com  ← Use YOUR email to receive it
   Password: TestPass123!
   Confirm:  TestPass123!
   ```
5. Click **Save**

### Expected Result ✅
- Page shows: **"Siswa berhasil ditambahkan dan email notifikasi telah dikirim."**
- Email arrives in inbox within 30 seconds with:
  - Professional HTML design (gradient header)
  - Your name
  - Login credentials
  - Clear instructions

### If Email Doesn't Arrive
Check logs:
```bash
# In terminal
tail -f storage/logs/laravel.log | grep -i "student account"
```

Look for line with `"email_sent":true`. If false, you'll see the error message.

---

## 🧪 Test 2: Password Reset Email

### How to Test
1. Open browser → https://alwi-college.vercel.app/login
2. Click **"Forgot Password?"**
3. Enter email address: `test@example.com` (or any existing user email)
4. Click **"Send Password Reset Link"**

### Expected Result ✅
- Page shows: **"Password reset link has been sent to your email"**
- Email arrives in inbox with:
  - Reset password button/link
  - Valid for 60 minutes
  - Professional design

### If Email Doesn't Arrive
Check logs:
```bash
tail -f storage/logs/laravel.log | grep -i "password reset"
```

Look for: `"Password reset email sent successfully"` or error message.

---

## 📊 Configuration Verification

Your `.env` is PERFECT:
```
MAIL_MAILER=resend                                    ✅
RESEND_API_KEY=re_VMiD5VBz_8gA569jinvW3aTajdLCEJYSw ✅
MAIL_FROM_ADDRESS=onboarding@resend.dev              ✅
MAIL_FROM_NAME=Alwi College                          ✅
QUEUE_CONNECTION=sync                                ✅ (synchronous, not queued)
```

---

## 🔍 Troubleshooting

### Problem: Email still not arriving

**Step 1**: Check Resend Dashboard
- Go to https://resend.com/emails
- Look for your test emails
- If they're there but not in your inbox, check spam folder

**Step 2**: Verify API Key
- Go to https://resend.com/api-keys
- Copy your active key
- Compare with `.env` value: `re_VMiD5VBz_8gA569jinvW3aTajdLCEJYSw`
- If different, update `.env`:
  ```
  RESEND_API_KEY=re_xxxxx_your_actual_key
  ```

**Step 3**: Check Laravel Logs
```bash
cd d:\TugasKp\Alwi-College
tail -f storage/logs/laravel.log
```

Search for keywords:
- "Failed to send" → error message shown
- "sent successfully" → email should have been sent
- "Exception" → check error details

**Step 4**: Test Manually in Terminal
```bash
# Start Laravel tinker
php artisan tinker

# Test account creation email
use App\Mail\AccountCreationEmail;
use Illuminate\Support\Facades\Mail;

Mail::to('your-email@gmail.com')->send(
    new AccountCreationEmail(
        userName: 'Test User',
        userEmail: 'test@example.com',
        password: 'TempPass123!',
        userType: 'siswa'
    )
);

# Test password reset email
use App\Mail\ResetPasswordNotification;

Mail::to('your-email@gmail.com')->send(
    new ResetPasswordNotification(
        resetUrl: 'https://example.com/reset/token',
        userName: 'Test User',
        expiresInMinutes: 60
    )
);
```

If emails arrive → Problem is in controller logic
If no emails → Problem is Resend configuration or API key

---

## 📝 Files Changed

**Account Creation Email**:
- ✅ `app/Mail/AccountCreationEmail.php` (Mailable class)
- ✅ `resources/views/emails/account-creation.blade.php` (Template)
- ✅ `app/Http/Controllers/AdminUserController.php` (storeStudent, storeTeacher)

**Password Reset Email**:
- ✅ `app/Mail/ResetPasswordNotification.php` (Removed ShouldQueue)
- ✅ `app/Models/User.php` (Fixed sendPasswordResetNotification method)

---

## 📋 Checklist Before Reporting Issues

- [ ] QUEUE_CONNECTION=sync (checked in `.env`)
- [ ] RESEND_API_KEY starts with `re_` (checked in `.env`)
- [ ] Checked Resend dashboard for emails (https://resend.com/emails)
- [ ] Checked spam folder
- [ ] Checked Laravel logs for error messages
- [ ] Tested manual email sending via tinker
- [ ] Cleared browser cache (hard refresh: Ctrl+Shift+Delete)

---

## 🚀 Deployment

When ready:
```bash
git push origin main
```

Vercel will auto-deploy. Email notifications will work in production! ✅

---

## 📞 Quick Reference

**Account Creation Flow**:
```
Admin creates student → AdminUserController::storeStudent()
  → Mail::to()->send(new AccountCreationEmail())
  → Blade template: emails.account-creation
  → Resend API sends email ✅
```

**Password Reset Flow**:
```
User clicks "Forgot Password" → ForgotPasswordController::sendResetLinkEmail()
  → User::sendPasswordResetNotification($token)
  → Mail::to()->send(new ResetPasswordNotification())
  → Blade template: emails.reset-password-notification
  → Resend API sends email ✅
```

Both now use **proper Mailable pattern** ✅
Both use **Resend driver correctly** ✅
Both have **proper error logging** ✅
