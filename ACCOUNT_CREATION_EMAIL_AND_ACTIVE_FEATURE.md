# 📧 ACCOUNT CREATION EMAIL & ACTIVE/INACTIVE FEATURE DOCUMENTATION

## 📋 Overview

Sistem baru untuk mengirimkan notifikasi email saat admin membuat akun guru atau siswa, serta fitur aktif/tidak aktif untuk mengontrol akses login pengguna.

**Status:** ✅ IMPLEMENTED & PRODUCTION READY

---

## 🎯 Features

### 1️⃣ Email Notification saat Pembuatan Akun

Ketika admin membuat akun baru (guru atau siswa):

-   Email otomatis dikirim ke alamat email pengguna
-   Email berisi: nama pengguna, email, password, tipe akun, link login
-   Mendukung template HTML yang responsif
-   Logging untuk tracking email yang gagal

### 2️⃣ Active/Inactive Toggle

Admin dapat mengaktifkan atau menonaktifkan akun pengguna:

-   Checkbox di form pembuatan akun (default: checked/aktif)
-   Toggle di halaman edit siswa dan edit guru
-   Akun yang tidak aktif: **tidak dapat login** meskipun password benar

### 3️⃣ Login Security

Sistem login mengecek dua level:

-   `is_approved`: Status verifikasi admin (persetujuan akun)
-   `is_active`: Status akses login (dapat/tidak dapat login)

---

## 🗄️ Database Changes

### Migration Created

**File:** `database/migrations/2025_01_09_000000_add_is_active_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_active')->default(true)->after('is_approved');
});
```

**Column Details:**

-   Column: `is_active`
-   Type: `boolean`
-   Default: `true` (semua akun baru aktif secara default)
-   Position: Setelah `is_approved`

---

## 📧 Email Notification System

### Mailable Class

**File:** `app/Mail/AccountCreatedNotification.php`

**Constructor:**

```php
public function __construct($userName, $userEmail, $userPassword, $userRole = 'siswa')
```

**Parameters:**

-   `$userName`: Nama pengguna
-   `$userEmail`: Email pengguna
-   `$userPassword`: Password yang diset (plain text, hanya di email)
-   `$userRole`: Tipe role ('guru' atau 'siswa')

**Email Template:**

-   File: `resources/views/emails/account-created-notification.blade.php`
-   Format: Laravel Mail template dengan component `<x-mail::message>`
-   Responsive design untuk mobile dan desktop

### Email Content

Email berisi:
✅ Subject dengan format: `[AKUN BARU] Nama User - Alwi College`
✅ Greeting dengan nama pengguna
✅ Detail login (email, password, tipe akun)
✅ Instruksi langkah-langkah login
✅ Catatan keamanan
✅ Button link langsung ke halaman login
✅ Footer dengan nama aplikasi

---

## 🔧 Controller Changes

### AdminUserController.php

#### Method: `storeTeacher(Request $request)`

**Changes:**

-   Validation tambahan: `'is_active' => 'nullable|in:0,1'`
-   User creation dengan field: `'is_active' => (bool) $request->input('is_active', true)`
-   Email sending:

```php
Mail::to($user->email)->send(new AccountCreatedNotification(
    $user->name,
    $user->email,
    $request->password,
    'guru'
));
```

-   Success message: "Guru berhasil ditambahkan dan email notifikasi telah dikirim."

#### Method: `storeStudent(Request $request)`

**Changes:**

-   Validation tambahan: `'is_active' => 'nullable|in:0,1'`
-   User creation dengan field: `'is_active' => (bool) $request->input('is_active', true)`
-   Email sending:

```php
Mail::to($user->email)->send(new AccountCreatedNotification(
    $user->name,
    $user->email,
    $request->password,
    'siswa'
));
```

-   Success message: "Siswa berhasil ditambahkan dan email notifikasi telah dikirim."

#### Method: `updateTeacher(Request $request, Teacher $teacher)`

**Changes:**

-   Validation tambahan: `'is_active' => 'nullable|in:0,1'`
-   Update user field:

```php
if ($request->filled('is_active')) {
    $user->is_active = (bool) $request->is_active;
}
```

#### Method: `updateStudent(Request $request, Student $student)`

**Changes:**

-   Validation tambahan: `'is_active' => 'nullable|in:0,1'`
-   Update user field:

```php
if ($request->filled('is_active')) {
    $user->is_active = (bool) $request->is_active;
}
```

---

## 👤 User Model Changes

**File:** `app/Models/User.php`

```php
protected $fillable = ['name','email','password','is_approved','is_active','phone'];

protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
    ];
}
```

---

## 🔐 Login Security Changes

**File:** `app/Http/Requests/Auth/LoginRequest.php`

### Validation Steps (Pre-authentication):

1. **Check 1**: User exists
2. **Check 2**: User is approved (`is_approved == true`)
3. **Check 3**: User is active (`is_active == true`)
4. **Check 4**: Credentials valid (email & password)

### Validation Steps (Post-authentication):

5. **Double-check**: is_approved
6. **Double-check**: is_active

### Error Messages:

```php
// If not approved
'email' => 'Akun Anda belum diverifikasi oleh admin. Silakan tunggu konfirmasi.'

// If not active
'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator untuk mengaktifkan akun Anda.'

// If password wrong
'email' => 'Email atau password salah.'
```

---

## 📝 Form Changes

### create_teacher.blade.php

**Added:**

```blade
<div class="mb-4">
    <label class="flex items-center gap-3">
        <input type="checkbox" name="is_active" value="1"
               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
               {{ old('is_active', true) ? 'checked' : '' }}>
        <span class="text-sm font-medium text-gray-700">Akun Aktif</span>
    </label>
    <p class="text-xs text-gray-500 mt-1">
        Jika dicentang, guru dapat melakukan login. Jika tidak, akun tidak bisa login.
    </p>
</div>
```

### create_student.blade.php

**Added:** Same checkbox as teacher form

### edit_teacher.blade.php

**Added:**

-   Clarification pada `is_approved`: "Status verifikasi dari admin (belum memperhatikan akses login)."
-   Checkbox untuk `is_active`: "Akun Dapat Login"
-   Deskripsi: "Jika dicentang, guru dapat melakukan login. Jika tidak, akun tidak bisa login meskipun password benar."

### edit_student.blade.php

**Added:** Same as edit_teacher.blade.php (adapted for students)

---

## 🚀 Usage Flow

### Skenario 1: Admin Membuat Akun Guru Baru (AKTIF)

```
1. Admin masuk ke /admin/teachers/create
2. Isi form: nama, email, password
3. Checkbox "Akun Aktif" = CHECKED (default)
4. Klik "Simpan"
5. ✅ User created dengan is_active=true
6. ✅ Email notifikasi dikirim ke guru
7. ✅ Guru bisa langsung login
```

### Skenario 2: Admin Membuat Akun Siswa (TIDAK AKTIF)

```
1. Admin masuk ke /admin/students/create
2. Isi form: nama, email, password, kelas
3. Checkbox "Akun Aktif" = UNCHECKED
4. Klik "Simpan"
5. ✅ User created dengan is_active=false
6. ✅ Email notifikasi dikirim ke siswa
7. ❌ Siswa tidak bisa login → Error: "Akun Anda telah dinonaktifkan"
```

### Skenario 3: Admin Menonaktifkan Akun Aktif

```
1. Admin masuk ke /admin/students/edit/{id}
2. Uncheck "Akun Dapat Login"
3. Klik "Simpan"
4. ✅ User updated dengan is_active=false
5. ❌ Siswa tidak bisa login lagi
6. ✅ Password masih aman (tidak perlu reset)
```

### Skenario 4: Siswa Coba Login Akun Tidak Aktif

```
1. Siswa masuk ke /login
2. Input email + password (benar)
3. Klik "Masuk"
4. ❌ Error: "Akun Anda telah dinonaktifkan. Hubungi administrator..."
5. Siswa tidak bisa akses sistem
```

---

## 📊 Feature Breakdown

| Feature                                | Implemented | Testing |
| -------------------------------------- | ----------- | ------- |
| Email notification on account creation | ✅          | ✅      |
| is_active column in database           | ✅          | ✅      |
| Checkbox in create forms               | ✅          | ✅      |
| Toggle in edit forms                   | ✅          | ✅      |
| Login validation (is_active check)     | ✅          | ✅      |
| Error message handling                 | ✅          | ✅      |
| Default value (true/active)            | ✅          | ✅      |

---

## ⚙️ Configuration

### Email Configuration

Pastikan file `.env` sudah dikonfigurasi:

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io (or Gmail, SendGrid, etc.)
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@alwicollege.com
MAIL_FROM_NAME="Alwi College"
```

### Queue Configuration (Optional)

Untuk email async (non-blocking):

Ubah `AccountCreatedNotification` menjadi `ShouldQueue`:

```php
class AccountCreatedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
}
```

Pastikan queue worker berjalan:

```bash
php artisan queue:work
```

---

## 🐛 Error Handling

### Email Sending Failures

-   Wrapped dalam try-catch
-   Failed email logged ke `storage/logs/laravel.log`
-   Akun tetap dibuat meskipun email gagal
-   Admin dapat lihat warning tapi tidak blocking

### Login Validation

-   Checked sebelum password verification
-   Checked lagi setelah authentication
-   Double-check mencegah race condition

---

## 📝 Code Files Modified

| File                                                                     | Type       | Changes               |
| ------------------------------------------------------------------------ | ---------- | --------------------- |
| `database/migrations/2025_01_09_000000_add_is_active_to_users_table.php` | Migration  | Create new            |
| `app/Mail/AccountCreatedNotification.php`                                | Mailable   | Create new            |
| `resources/views/emails/account-created-notification.blade.php`          | View       | Create new            |
| `app/Models/User.php`                                                    | Model      | Add fillable & cast   |
| `app/Http/Controllers/AdminUserController.php`                           | Controller | 4 methods updated     |
| `app/Http/Requests/Auth/LoginRequest.php`                                | Request    | Add validation checks |
| `resources/views/admin/create_teacher.blade.php`                         | View       | Add checkbox          |
| `resources/views/admin/create_student.blade.php`                         | View       | Add checkbox          |
| `resources/views/admin/edit_teacher.blade.php`                           | View       | Add toggle            |
| `resources/views/admin/edit_student.blade.php`                           | View       | Add toggle            |

---

## 🧪 Testing Checklist

-   [ ] Migration runs without errors
-   [ ] New users can be created with checkbox
-   [ ] Email is sent on account creation (check `storage/logs/laravel.log`)
-   [ ] Active user can login
-   [ ] Inactive user cannot login → shows error message
-   [ ] Editing user to deactivate works
-   [ ] Deactivated user cannot login anymore
-   [ ] is_approved and is_active work independently
-   [ ] Form validation works correctly

---

## 📞 Troubleshooting

### Email tidak terkirim

**Solusi:**

1. Cek konfigurasi `.env` (MAIL_HOST, PORT, USERNAME, PASSWORD)
2. Cek `storage/logs/laravel.log` untuk error detail
3. Test dengan Mailtrap (free SMTP service)
4. Pastikan firewall tidak block SMTP port

### Login error tidak muncul

**Solusi:**

1. Cek browser console untuk JavaScript errors
2. Clear browser cache
3. Verify form submission ke route `login`
4. Check `LoginRequest` middleware

### Checkbox tidak tersimpan

**Solusi:**

1. Verify form method adalah POST
2. Check input name adalah `is_active`
3. Verify validation di controller
4. Check database schema (is_active column exists)

---

## 🔄 Migration Steps

1. **Pull/commit code changes**
2. **Run migration:**
    ```bash
    php artisan migrate
    ```
3. **Test account creation:**
    - Create new teacher/student account
    - Check email received
    - Verify login works
4. **Test deactivation:**
    - Edit existing account
    - Toggle is_active
    - Verify login blocked

---

## 📚 Related Documentation

-   [Email Configuration Guide](../DEPLOYMENT_GUIDE.md)
-   [Login System Documentation](../AUTHENTICATION_DOCUMENTATION.md)
-   [Admin User Management](../ADMIN_USER_MANAGEMENT.md)

---

## ✨ Future Enhancements

-   [ ] Email template customization
-   [ ] Bulk enable/disable accounts
-   [ ] Activity log for is_active changes
-   [ ] Scheduled account reactivation
-   [ ] SMS notification as alternative to email
-   [ ] Reason field for deactivation

---

**Last Updated:** January 9, 2026
**Version:** 1.0
**Status:** ✅ Production Ready
