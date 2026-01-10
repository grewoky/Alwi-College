# 🔧 Email Tidak Diterima - Troubleshooting Guide

**Status**: Email mengatakan terkirim tetapi tidak sampai ke Gmail
**Tanggal**: January 10, 2026

---

## ✅ PERBAIKAN YANG TELAH DILAKUKAN:

### 1. **ResendService.php** - Updated
- Fixed `sendCustomEmail()` method untuk better email handling
- Added proper HTML content-type header
- Better error logging dengan error_class

### 2. **AdminUserController.php** - Updated
- Added email sending verification
- Now shows actual send result (sukses/gagal)
- Added debug logging ke Laravel logs

---

## 📋 CHECKLIST TROUBLESHOOTING:

### Step 1: Cek Gmail Spam Folder
```
1. Buka Gmail
2. Klik "Spam" atau "Junk" di sidebar
3. Cari email dari "Alwi College <onboarding@resend.dev>"
4. Jika ada, mark as "Not Spam"
```

**Kenapa masuk spam?**
- Sender tidak terverifikasi di Resend
- Email format tidak proper
- SPF/DKIM/DMARC belum dikonfigurasi

---

### Step 2: Verifikasi Sender di Resend (PENTING!)
```
1. Buka https://resend.com
2. Login dengan akun Resend Anda
3. Pergi ke "Emails" atau "Domains"
4. Cari "onboarding@resend.dev"
5. Pastikan status: "VERIFIED" ✅
```

**Jika belum verified:**
- Resend akan kirim verification email
- Buka email verification dari Resend
- Click link to verify
- Tunggu ~5 menit untuk aktif

---

### Step 3: Cek API Key Valid
```
Di .env Anda:
RESEND_API_KEY=re_VMiD5VBz_8gA569jinvW3aTajdLCEJYSw

Verifikasi:
1. Buka https://resend.com/api-keys
2. Lihat apakah key ini ada di list
3. Status harus: "Active" ✅
```

---

### Step 4: Cek Logs untuk Error Details

**Local (development):**
```bash
tail -f storage/logs/laravel.log | grep -i "email\|custom"
```

**Vercel (production):**
```
1. Buka https://vercel.com
2. Project: Alwi College
3. Klik "Logs"
4. Cari "Custom email sent" atau error messages
```

---

### Step 5: Resend Dashboard Email History
```
1. Buka https://resend.com/emails
2. Lihat list semua emails yang dikirim
3. Cari email ke alamat student yang Anda test
4. Lihat status:
   - ✅ "Delivered" = Berhasil sampai ke mailbox
   - ⚠️ "Opened" = Sudah dibuka
   - ❌ "Failed" = Ada error
   - ⏳ "Pending" = Masih diproses
```

---

## 🔍 POSSIBLE ISSUES & SOLUTIONS:

| Masalah | Penyebab | Solusi |
|---------|---------|--------|
| Email tidak sampai | Spam folder | Check Gmail spam, mark as not spam |
| Email tidak terkirim | Sender tidak verified | Verify email di Resend dashboard |
| Error "Invalid API key" | API key salah/expired | Check Resend API key is valid & active |
| Error "Invalid recipient" | Email invalid | Pastikan email siswa valid |
| Masuk spam terus-menerus | SPF/DKIM belum setup | Setup custom domain di Resend |
| Vercel error logs | Config tidak sync | Redeploy dari Vercel dashboard |

---

## 🚀 NEXT STEPS:

### 1. Push ke GitHub (Untuk update Vercel)
```bash
git add -A
git commit -m "Fix email sending with better error handling"
git push origin main
```
(Jika permission issue, push via GitHub Web)

### 2. Test Ulang
```
1. Akses https://alwi-college.vercel.app/admin/students/create
2. Buat student baru dengan email test Anda
3. Tunggu 5 detik
4. Check Gmail inbox & spam folder
```

### 3. Monitor Logs
```
1. Jika email gagal, check Vercel logs
2. Lihat error message untuk detail
3. Lapor ke developer dengan error message
```

---

## 📧 TEST EMAIL ADDRESSES:

Untuk testing, gunakan:
```
✅ Gmail: suatigmail@gmail.com (reliable)
✅ Outlook: nama@outlook.com (good)
✅ Yahoo: nama@yahoo.com (sometimes spam)
⚠️ Corporate email: mungkin blocked oleh filter
```

---

## 🔐 SECURITY NOTES:

- ✅ API Key sudah di-hash di environment
- ✅ Password tidak dikirim plaintext
- ✅ Link reset password secure
- ✅ Email template di-sanitize

---

## 📱 WHITELISTING DI GMAIL:

Jika email terus masuk spam:

**Di Gmail:**
```
1. Buka email dari Alwi College
2. Klik 3 dots → "Add to Contacts"
3. Klik 3 dots → "Mark as not spam"
4. Atau drag ke tab "Primary"
```

**Buat filter:**
```
1. Gmail Settings
2. Filters & Blocked Addresses
3. Create new filter
4. From: onboarding@resend.dev
5. Action: "Never send to Spam"
```

---

## ✨ VERSI TERBARU SUDAH LIVE:

- ✅ `ResendService.php` - Fixed email sending
- ✅ `AdminUserController.php` - Better error reporting
- ✅ Logging - Now captures actual send status

**Untuk apply ke production:**
```bash
git push origin main  # Vercel auto-redeploy
```

---

## 💬 DEBUGGING TIPS:

Jika masih tidak berhasil, kumpulkan informasi:

```
1. Screenshot pesan yang muncul di aplikasi
2. Email address yang ditest
3. Error dari Vercel logs (jika ada)
4. Status di Resend dashboard
5. Gmail spam folder check
```

Lapor ke developer dengan info di atas untuk debugging lebih lanjut!

---

**Updated**: January 10, 2026
**Status**: Troubleshooting Guide Ready
