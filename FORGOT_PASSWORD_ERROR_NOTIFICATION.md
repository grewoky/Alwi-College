# 📧 Sistem Notifikasi Error Forgot Password ke Gmail

## 📋 Ringkasan Fitur

Telah ditambahkan sistem notifikasi error otomatis ke Gmail admin ketika terjadi error pada fitur "Lupa Password". Sistem ini akan mengirimkan email detail error kepada admin dengan informasi lengkap tentang apa yang terjadi.

## 🔧 Fitur yang Ditambahkan

### 1. **Mailable Class** - `ForgotPasswordErrorNotification.php`

**Lokasi**: `app/Mail/ForgotPasswordErrorNotification.php`

Kelas ini bertanggung jawab untuk membuat dan mengirim email notifikasi error ke admin.

**Parameter yang diterima:**

-   `$email` - Email user yang melakukan request lupa password
-   `$errorMessage` - Pesan error yang terjadi
-   `$errorCode` - Kode error (default: 'UNKNOWN_ERROR')

### 2. **Email Template** - `forgot-password-error.blade.php`

**Lokasi**: `resources/views/emails/forgot-password-error.blade.php`

Template HTML profesional yang menampilkan:

-   ⚠️ Header dengan warning icon
-   ❌ Pesan error yang detail
-   📧 Email pengguna yang bermasalah
-   🕐 Waktu terjadinya error
-   🌍 Informasi aplikasi
-   📋 Rekomendasi tindakan

### 3. **Controller Update** - `PasswordResetLinkController.php`

**Lokasi**: `app/Http/Controllers/Auth/PasswordResetLinkController.php`

**Perubahan:**

-   Menambahkan try-catch untuk menangkap exception
-   Logging error ke system untuk debugging
-   Mengirim notifikasi error ke admin email
-   Menampilkan pesan user-friendly ke user

## 📧 Konfigurasi Email

### Setup untuk Gmail (SMTP)

Tambahkan/ubah konfigurasi di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Admin email untuk menerima notifikasi error
ADMIN_EMAIL=admin@alwicollege.com
```

### Langkah-langkah Setup Gmail:

#### 1. **Aktifkan 2-Factor Authentication**

-   Buka Google Account: https://myaccount.google.com/
-   Klik "Security" di sidebar kiri
-   Aktifkan "2-Step Verification"

#### 2. **Buat App Password**

-   Setelah 2FA aktif, kembali ke Security
-   Cari "App passwords"
-   Pilih "Mail" dan "Windows Computer" (atau device Anda)
-   Google akan generate password unik (16 karakter)
-   Gunakan password ini di `MAIL_PASSWORD`

#### 3. **Test Connection**

```bash
php artisan tinker
Mail::raw('Test email', function ($message) {
    $message->to('admin@example.com')->subject('Test');
});
# exit (Ctrl+C)
```

### Alternative: Menggunakan Mailtrap atau Sendgrid

**Mailtrap (Development):**

```env
MAIL_MAILER=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

**SendGrid (Production):**

```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your_sendgrid_api_key
```

## 🔄 Alur Kerja

```
User Submit Email Lupa Password
        ↓
   Try Block
        ↓
  Error Terjadi?
    ↙      ↘
  Ya        Tidak
   ↓         ↓
  Catch   Return Success
   ↓
  Log Error
   ↓
  Send Email to Admin
   ↓
  Show User-Friendly Error
```

## 📝 Error yang Ditangani

Sistem dapat menangkap dan mengirim notifikasi untuk:

1. **Invalid Email Format** - Format email tidak valid
2. **User Not Found** - Email tidak terdaftar di sistem
3. **SMTP Connection Error** - Gagal koneksi ke email server
4. **Rate Limiting** - Terlalu banyak request reset password
5. **Database Error** - Error saat query database
6. **Custom Exceptions** - Exception apapun yang terjadi

## 📧 Konten Email yang Dikirim

Email akan berisi:

```
Header: ⚠️ Notifikasi Error - Lupa Password
─────────────────────────────

❌ Error Message: [Pesan error yang sebenarnya]
Error Code: [Kode error]

📧 Email Pengguna: user@example.com
🕐 Waktu Terjadinya: 09-01-2026 14:30:45
🌍 Aplikasi: Alwi College
🔗 URL: http://localhost

─────────────────────────────
Tindakan Rekomendasi:
• Periksa konfigurasi email sistem
• Verifikasi kredensial email
• Pastikan user terdaftar
• Cek log aplikasi
• Hubungi developer jika perlu
```

## 🔐 Logging

Semua error dan aktivitas di-log di:

-   **File Log**: `storage/logs/laravel.log`
-   **Level**: ERROR (untuk exceptions), INFO (untuk success notifications)

**Contoh Log:**

```
[2026-01-09 14:30:45] local.ERROR: Forgot Password Error {"email":"user@example.com","error_message":"User not found","error_code":"USER_NOT_FOUND",...}
[2026-01-09 14:31:12] local.INFO: Error notification sent to admin {"admin_email":"admin@alwicollege.com",...}
```

## ✅ Testing

### Manual Testing

1. **Akses halaman forgot password:**

    ```
    http://localhost/forgot-password
    ```

2. **Masukkan email yang tidak terdaftar** atau atur konfigurasi email salah

3. **Verifikasi:**
    - User melihat pesan error yang ramah
    - Admin menerima email notifikasi error
    - Log tersimpan di `storage/logs/laravel.log`

### Artisan Testing

```bash
# Cek konfigurasi email
php artisan config:show mail

# Test send email
php artisan tinker
Mail::to('admin@example.com')->send(new \App\Mail\ForgotPasswordErrorNotification('user@example.com', 'Test Error', 'TEST_CODE'));
# exit
```

## 📁 File yang Ditambahkan/Dimodifikasi

### Ditambahkan:

1. ✅ `app/Mail/ForgotPasswordErrorNotification.php` - Mailable class
2. ✅ `resources/views/emails/forgot-password-error.blade.php` - Email template

### Dimodifikasi:

1. ✅ `app/Http/Controllers/Auth/PasswordResetLinkController.php` - Controller logic

### Konfigurasi (perlu ditambahkan di `.env`):

```env
ADMIN_EMAIL=admin@alwicollege.com
```

## 🚀 Deployment Checklist

-   [ ] Setup SMTP di production server
-   [ ] Konfigurasi email credentials di `.env` production
-   [ ] Test email notifikasi di staging
-   [ ] Verify admin email address benar
-   [ ] Check storage/logs/ writable
-   [ ] Monitor log files untuk errors
-   [ ] Setup email forwarding jika diperlukan

## 🔗 Referensi Dokumentasi

-   [Laravel Mail Documentation](https://laravel.com/docs/mail)
-   [Laravel Mailable Classes](https://laravel.com/docs/mail#mailable-class)
-   [Gmail App Passwords](https://support.google.com/accounts/answer/185833)
-   [Laravel Logging](https://laravel.com/docs/logging)

## 📞 Support

Jika ada pertanyaan atau masalah:

1. Cek file log di `storage/logs/laravel.log`
2. Verifikasi konfigurasi email di `.env`
3. Test koneksi SMTP menggunakan artisan tinker
4. Hubungi tim teknis

---

**Status**: ✅ Implementasi Selesai  
**Tanggal**: 9 January 2026  
**Version**: 1.0
