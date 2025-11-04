# 📅 SISTEM PENGHAPUSAN JADWAL OTOMATIS

**Alwi College Management System**

---

## 📋 Ringkasan Sistem

Sistem ini **secara otomatis menghapus jadwal yang sudah lewat tanggalnya setiap hari pukul 00:30 (jam 12:30 pagi)**.

```
FITUR UTAMA:
✅ Hapus jadwal otomatis setiap hari
✅ Penjadwalan bisa dilakukan jauh sebelumnya (seminggu, sebulan sebelumnya)
✅ Log tracking untuk semua jadwal yang dihapus
✅ Manual delete option untuk admin
✅ View untuk melihat jadwal yang akan dihapus
```

---

## 🏗️ ARSITEKTUR SISTEM

### **KOMPONEN 1: Artisan Command (Scheduler)**

```
File: app/Console/Commands/DeleteExpiredLessons.php

Fungsi:
├─ Command Name: schedule:cleanup
├─ Trigger: Setiap hari pukul 00:30
├─ Action: Hapus semua jadwal dengan lesson_date < hari ini
└─ Logging: Simpan ke file log dan database
```

### **KOMPONEN 2: Scheduler Configuration**

```
File: app/Console/Kernel.php

Konfigurasi:
├─ Frequency: daily() - Sekali per hari
├─ Time: at('00:30') - Pukul 00:30 pagi
├─ Overlapping: withoutOverlapping() - Cegah double run
├─ Error: onFailure() - Callback jika gagal
└─ Success: onSuccess() - Callback jika berhasil
```

### **KOMPONEN 3: Tracking Database**

```
File: database/migrations/2025_11_04_120000_create_deleted_lessons_log_table.php

Tabel: deleted_lessons_log

Kolom:
├─ id (Primary Key)
├─ lesson_date (date) - Tanggal jadwal yang dihapus
├─ classroom_id (foreign key)
├─ teacher_id (foreign key)
├─ subject_id (foreign key, nullable)
├─ start_time (time, nullable)
├─ end_time (time, nullable)
├─ deleted_at (timestamp) - Kapan dihapus
├─ deleted_by (string) - 'system' atau user_id
├─ deletion_reason (text) - Alasan penghapusan
└─ timestamps (created_at, updated_at)
```

### **KOMPONEN 4: Controller Methods**

```
File: app/Http/Controllers/LessonController.php

Method Baru:
├─ showExpiredLessons() → GET /admin/jadwal/will-delete
│  └─ Tampilkan jadwal yang akan dihapus
│
├─ showDeletedLog() → GET /admin/jadwal/delete-log
│  └─ Tampilkan log semua jadwal yang sudah dihapus
│
└─ destroyManual($id) → DELETE /admin/jadwal/{id}
   └─ Manual delete jadwal tertentu (admin only)
```

---

## 🔄 ALUR KERJA

### **TIMELINE CONTOH**

```
SCENARIO: Generate jadwal untuk Bulan November 2025 pada Tanggal 1 November

═══════════════════════════════════════════════════════════════════════

📅 HARI PERTAMA - Rabu, 01 November 2025
┌─────────────────────────────────────────────────────────────────────┐
│ Admin membuka form Generate Jadwal                                  │
│ ├─ Grade: 11                                                         │
│ ├─ Room Code: A21                                                    │
│ ├─ Teacher: Budi                                                     │
│ ├─ Start Date: 2025-11-01 ✅                                        │
│ ├─ End Date: 2025-11-30 ✅ (29 hari kedepan!)                      │
│ └─ Submit                                                            │
│                                                                      │
│ Result: 30 record Lesson dibuat di database                         │
│ ├─ lesson_date: 2025-11-01 sampai 2025-11-30                       │
│ ├─ Semua dengan class_room_id, teacher_id yang sama                 │
│ └─ Semua masih aktif dan bisa diakses                               │
└─────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════

📅 HARI KEDUA - Kamis, 02 November 2025 (pukul 00:30)
┌─────────────────────────────────────────────────────────────────────┐
│ 🔔 SCHEDULER TRIGGERS (pukul 00:30)                                 │
│                                                                      │
│ Command: schedule:cleanup berjalan otomatis                         │
│ ├─ Query: SELECT * FROM lessons WHERE lesson_date < '2025-11-02'   │
│ ├─ Result: 1 record (lesson_date = 2025-11-01)                     │
│ ├─ Action:                                                           │
│ │  ├─ Simpan ke deleted_lessons_log (dengan alasan & timestamp)    │
│ │  └─ Hapus dari lessons table                                     │
│ ├─ Log: "✅ Cleanup selesai! 1 jadwal dihapus"                      │
│ └─ Next run: Besok pukul 00:30                                      │
└─────────────────────────────────────────────────────────────────────┘

Status Database lessons setelah cleanup:
├─ 2025-11-01: ❌ DIHAPUS
├─ 2025-11-02 sampai 2025-11-30: ✅ MASIH ADA (29 jadwal)
└─ Total: 29 jadwal tersisa

═══════════════════════════════════════════════════════════════════════

📅 HARI KETIGA - Jumat, 03 November 2025 (pukul 00:30)
┌─────────────────────────────────────────────────────────────────────┐
│ 🔔 SCHEDULER TRIGGERS LAGI                                          │
│                                                                      │
│ Query: SELECT * FROM lessons WHERE lesson_date < '2025-11-03'      │
│ Result: 2 records (2025-11-01 sudah dihapus, sekarang 2025-11-02)  │
│ Action:                                                              │
│ ├─ Simpan 2025-11-02 ke deleted_lessons_log                        │
│ ├─ Hapus 2025-11-02 dari lessons                                   │
│ └─ Log: "✅ Cleanup selesai! 1 jadwal dihapus"                      │
│                                                                      │
│ Status: 2025-11-03 sampai 2025-11-30 masih ada (28 jadwal)        │
└─────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════

📅 HARI TERAKHIR - Minggu, 30 November 2025 (pukul 00:30)
┌─────────────────────────────────────────────────────────────────────┐
│ 🔔 SCHEDULER TRIGGERS LAGI                                          │
│                                                                      │
│ Query: SELECT * FROM lessons WHERE lesson_date < '2025-11-30'      │
│ Result: 29 records (semua jadwal kecuali yang hari ini)             │
│ Action:                                                              │
│ ├─ Simpan 29 records ke deleted_lessons_log                        │
│ ├─ Hapus 29 records dari lessons                                   │
│ └─ Log: "✅ Cleanup selesai! 29 jadwal dihapus"                     │
│                                                                      │
│ Status: Hanya 1 jadwal tersisa (2025-11-30)                        │
└─────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════

📅 HARI PERTAMA DESEMBER - Senin, 01 Desember 2025 (pukul 00:30)
┌─────────────────────────────────────────────────────────────────────┐
│ 🔔 SCHEDULER TRIGGERS LAGI                                          │
│                                                                      │
│ Query: SELECT * FROM lessons WHERE lesson_date < '2025-12-01'      │
│ Result: 1 record (2025-11-30 sudah lewat)                          │
│ Action:                                                              │
│ ├─ Simpan 2025-11-30 ke deleted_lessons_log                        │
│ ├─ Hapus 2025-11-30 dari lessons                                   │
│ └─ Log: "✅ Cleanup selesai! 1 jadwal dihapus"                      │
│                                                                      │
│ Status: 0 jadwal tersisa (semua dihapus)                           │
│                                                                      │
│ ✓ Data tetap tersimpan di deleted_lessons_log untuk audit trail    │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 💾 DATABASE FLOW

```
SEBELUM CLEANUP:
┌─ lessons table (30 records)
│  ├─ 2025-11-01, classroom 5, teacher 3
│  ├─ 2025-11-02, classroom 5, teacher 3
│  ├─ ...
│  └─ 2025-11-30, classroom 5, teacher 3
│
└─ deleted_lessons_log table (0 records)

SESUDAH CLEANUP (Hari ke-2):
┌─ lessons table (29 records)
│  ├─ 2025-11-02, classroom 5, teacher 3 ✓
│  ├─ 2025-11-03, classroom 5, teacher 3 ✓
│  ├─ ...
│  └─ 2025-11-30, classroom 5, teacher 3 ✓
│
└─ deleted_lessons_log table (1 record)
   └─ lesson_date: 2025-11-01
      deleted_at: 2025-11-02 00:30:15
      deleted_by: system
      reason: Automated cleanup - lesson date has passed
```

---

## 🛠️ SETUP & INSTALASI

### **STEP 1: Jalankan Migration**

```bash
php artisan migrate

# Output:
# ✓ Creating table deleted_lessons_log
# ✓ Migration successful
```

### **STEP 2: Test Command (Manual)**

```bash
php artisan schedule:cleanup

# Output:
# 🔄 Memulai cleanup jadwal yang sudah lewat...
# ✅ Cleanup selesai! X jadwal yang sudah lewat berhasil dihapus.
```

### **STEP 3: Enable Scheduler (Production)**

Di production server, tambahkan cron job:

```bash
# Edit crontab
crontab -e

# Tambahkan line ini:
* * * * * cd /path/to/alwi-college && php artisan schedule:run >> /dev/null 2>&1
```

**Penjelasan:**

-   `* * * * *` = Jalankan setiap menit
-   `php artisan schedule:run` = Jalankan semua scheduled tasks
-   Laravel akan membaca `Kernel.php` dan menjalankan `schedule:cleanup` pada pukul 00:30 saja

### **STEP 4: Verify Cron (Optional)**

```bash
# Check apakah cron sudah berjalan
grep CRON /var/log/syslog

# Atau check Laravel logs
tail -f storage/logs/laravel.log
```

---

## 📊 CARA MENGGUNAKAN

### **1. View Jadwal yang Akan Dihapus**

```
URL: GET /admin/jadwal/will-delete

Apa yang ditampilkan:
├─ List jadwal dengan lesson_date < hari ini
├─ Classroom, Teacher, Subject info
├─ Tanggal jadwal
├─ Total jadwal yang akan dihapus
└─ Pagination (20 per halaman)
```

### **2. View Log Jadwal Dihapus**

```
URL: GET /admin/jadwal/delete-log

Apa yang ditampilkan:
├─ History semua jadwal yang sudah dihapus
├─ Tanggal jadwal yang dihapus
├─ Kapan dihapus (deleted_at)
├─ Siapa yang menghapus (system / user email)
├─ Alasan penghapusan
└─ Total jadwal dihapus (all time)
```

### **3. Manual Delete Jadwal**

```
URL: DELETE /admin/jadwal/{id}

Action:
├─ Hapus jadwal tertentu secara manual
├─ Catat ke deleted_lessons_log dengan deleted_by = user email
├─ Log aktivitas ke file
└─ Redirect dengan success message
```

---

## 🧪 TESTING

### **TESTING SCENARIO 1: Automatic Cleanup**

```
Step 1: Buat jadwal untuk 5 hari ke depan
├─ POST /admin/jadwal/generate
├─ Grade: 11
├─ Room Code: A21
├─ Start Date: 2025-11-04
├─ End Date: 2025-11-08
└─ Result: 5 jadwal dibuat

Step 2: Check jadwal yang akan dihapus
├─ GET /admin/jadwal/will-delete
└─ Result: 0 jadwal (semua masih aktif)

Step 3: Manually trigger cleanup
├─ php artisan schedule:cleanup
└─ Result: "✓ Tidak ada jadwal yang perlu dihapus"

Step 4: Ubah tanggal sistem menjadi 2 hari kemudian
├─ Misal: mengubah NOW() di database
├─ Atau menggunakan Carbon::setTestNow()
└─ (Untuk development testing)

Step 5: Cek jadwal yang akan dihapus lagi
├─ GET /admin/jadwal/will-delete
└─ Result: 2 jadwal ditampilkan (2025-11-04 dan 2025-11-05)

Step 6: Jalankan cleanup lagi
├─ php artisan schedule:cleanup
├─ Result: "✅ Cleanup selesai! 2 jadwal dihapus"
└─ Cek: SELECT COUNT(*) FROM deleted_lessons_log → 2 records

Step 7: Verify jadwal dihapus dari lessons table
├─ SELECT COUNT(*) FROM lessons → harus berkurang 2
├─ Sisa jadwal: 2025-11-06, 2025-11-07, 2025-11-08
└─ deleted_lessons_log: 2 records dengan alasan "Automated cleanup"
```

### **TESTING SCENARIO 2: Manual Delete**

```
Step 1: Lihat jadwal untuk dihapus
├─ GET /admin/jadwal/will-delete
└─ Result: Lihat list jadwal

Step 2: Manual delete 1 jadwal
├─ DELETE /admin/jadwal/3
├─ Result: "✅ Jadwal berhasil dihapus"
└─ deleted_by: admin@example.com

Step 3: Check deleted log
├─ GET /admin/jadwal/delete-log
├─ Result: 1 record (atau lebih jika sebelumnya sudah ada)
└─ deleted_by: admin@example.com
└─ reason: Manual deletion by admin
```

### **TESTING SCENARIO 3: Cron Job (Production)**

```
Linux/Mac Command Line:
$ crontab -e

Add:
* * * * * cd /var/www/alwi-college && php artisan schedule:run >> /dev/null 2>&1

Then wait until next minute, check logs:
$ tail -f storage/logs/laravel.log | grep schedule:cleanup

Expected Output:
[2025-11-04 00:30:15] local.INFO: DeleteExpiredLessons: Success {"deleted_count":5,"executed_at":"2025-11-04 00:30:15"}
```

---

## ⚙️ KONFIGURASI LANJUTAN

### **Mengubah Waktu Cleanup**

Edit `app/Console/Kernel.php`:

```php
// Default: 00:30 (jam 12:30 pagi)
$schedule->command('schedule:cleanup')
         ->daily()
         ->at('00:30')  // ← Ubah waktu di sini
         ->withoutOverlapping();

// CONTOH ALTERNATIF:
// Cleanup jam 6 pagi
->at('06:00')

// Cleanup jam 10 malam
->at('22:00')

// Cleanup jam 1 siang
->at('13:00')
```

### **Mengubah Kondisi Deletion**

Edit `app/Console/Commands/DeleteExpiredLessons.php`:

```php
// Saat ini: Hapus jadwal dengan lesson_date < hari ini
$expiredLessons = Lesson::where('lesson_date', '<', $today->toDateString())->get();

// ALTERNATIF 1: Hapus jadwal lebih dari 7 hari yang lalu
$sevenDaysAgo = Carbon::now()->subDays(7)->startOfDay();
$expiredLessons = Lesson::where('lesson_date', '<', $sevenDaysAgo->toDateString())->get();

// ALTERNATIF 2: Hapus jadwal lebih dari 30 hari yang lalu
$thirtyDaysAgo = Carbon::now()->subDays(30)->startOfDay();
$expiredLessons = Lesson::where('lesson_date', '<', $thirtyDaysAgo->toDateString())->get();

// ALTERNATIF 3: Hapus jadwal berdasarkan bulan sebelumnya
$lastMonth = Carbon::now()->subMonth()->endOfMonth();
$expiredLessons = Lesson::where('lesson_date', '<', $lastMonth->toDateString())->get();
```

### **Disable Logging**

Jika tidak ingin menyimpan ke `deleted_lessons_log`:

```php
// Di DeleteExpiredLessons.php, comment out bagian:
/*
if (Schema::hasTable('deleted_lessons_log')) {
    DB::table('deleted_lessons_log')->insert([...]);
}
*/
```

---

## 🔍 MONITORING & TROUBLESHOOTING

### **Check Jadwal yang Akan Dihapus**

```bash
# Via PHP tinker
php artisan tinker

>>> use App\Models\Lesson;
>>> use Carbon\Carbon;
>>> $today = Carbon::now()->startOfDay();
>>> Lesson::where('lesson_date', '<', $today->toDateString())->count();
// Output: 5

>>> Lesson::where('lesson_date', '<', $today->toDateString())->get();
// Output: Collection dengan 5 jadwal
```

### **Check Log Delete**

```bash
# Via Database
SELECT * FROM deleted_lessons_log ORDER BY deleted_at DESC LIMIT 10;

# Via Laravel Tinker
>>> DB::table('deleted_lessons_log')->latest('deleted_at')->limit(5)->get();
```

### **Check Cron Job Status**

```bash
# View cron logs
grep CRON /var/log/syslog | tail -20

# View Laravel logs
tail -f storage/logs/laravel.log

# Search untuk schedule:cleanup
grep "schedule:cleanup" storage/logs/laravel.log
```

### **Troubleshooting**

**❌ Cron tidak berjalan:**

```bash
# Check apakah cron service aktif
sudo service cron status
sudo systemctl status cron

# Restart cron
sudo service cron restart
```

**❌ Command gagal terus:**

```bash
# Test command manually
php artisan schedule:cleanup

# Lihat error detail
php artisan tinker
>>> php artisan schedule:cleanup --verbose
```

**❌ Permission denied:**

```bash
# Set permission untuk storage
chmod -R 775 storage/

# Set permission untuk logs
chmod 666 storage/logs/laravel.log
```

---

## 📋 FILE CHECKLIST

```
✅ YANG SUDAH DIBUAT:

1. app/Console/Commands/DeleteExpiredLessons.php
   └─ Command untuk menghapus jadwal expired

2. app/Console/Kernel.php
   └─ Konfigurasi scheduler (daily, at 00:30)

3. database/migrations/2025_11_04_120000_create_deleted_lessons_log_table.php
   └─ Table untuk tracking deleted lessons

4. app/Http/Controllers/LessonController.php
   └─ Tambahan 3 method: showExpiredLessons(), showDeletedLog(), destroyManual()

5. routes/web.php atau routes/api.php
   └─ PERLU DITAMBAHKAN:
      - GET /admin/jadwal/will-delete → showExpiredLessons()
      - GET /admin/jadwal/delete-log → showDeletedLog()
      - DELETE /admin/jadwal/{id} → destroyManual()

6. resources/views/lessons/expired.blade.php
   └─ PERLU DIBUAT: View untuk menampilkan jadwal yang akan dihapus

7. resources/views/lessons/deleted-log.blade.php
   └─ PERLU DIBUAT: View untuk menampilkan log jadwal dihapus

8. resources/views/lessons/generate.blade.php
   └─ ✅ SUDAH ADA (tidak perlu diubah)
```

---

## 📌 SUMMARY

| Aspek                   | Detail                                   |
| ----------------------- | ---------------------------------------- |
| **Frekuensi Cleanup**   | Setiap hari pukul 00:30 (jam 12:30 pagi) |
| **Jadwal yang Dihapus** | Jadwal dengan lesson_date < hari ini     |
| **Jadwal Bisa Dibuat**  | Jauh sebelumnya (seminggu, sebulan, dll) |
| **Tracking**            | Simpan ke deleted_lessons_log table      |
| **Manual Delete**       | Admin bisa hapus jadwal tertentu via UI  |
| **Logs**                | File log + database logging              |
| **Cron Job**            | Butuh setup di production server         |
| **Setup Effort**        | 5 menit (migration + run test)           |

---

**🎉 Sistem siap digunakan setelah menjalankan `php artisan migrate`!**
