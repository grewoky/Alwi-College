# 🎯 SISTEM PENGHAPUSAN JADWAL - PENJELASAN LENGKAP

**Dibuat: 4 November 2025**
**Status: ✅ READY TO USE**

---

## 📌 OVERVIEW SINGKAT

```
MASALAH:
  Jadwal terlalu banyak di database dan tidak pernah dihapus

SOLUSI:
  Sistem otomatis menghapus jadwal yang tanggalnya sudah lewat
  - Berjalan SETIAP HARI pukul 00:30 (jam 12:30 pagi)
  - Jadwal bisa dibuat jauh sebelumnya (minggu, bulan depan)
  - Semua jadwal yang dihapus dicatat di log (history)

KEUNTUNGAN:
  ✓ Database tetap bersih
  ✓ Data tidak hilang (tersimpan di history)
  ✓ Penjadwalan fleksibel
  ✓ Admin bisa monitoring dan manual delete
```

---

## 🏗️ ARSITEKTUR (4 KOMPONEN UTAMA)

```
┌─────────────────────────────────────────────────────────────────┐
│                   SISTEM PENGHAPUSAN JADWAL                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1️⃣ COMMAND (Artisan)                                          │
│     File: app/Console/Commands/DeleteExpiredLessons.php        │
│     │                                                            │
│     ├─ Nama: schedule:cleanup                                  │
│     ├─ Fungsi: Hapus jadwal expired                            │
│     ├─ Jalankan: php artisan schedule:cleanup                 │
│     └─ Status: ✅ SELESAI & TESTED                             │
│                                                                 │
│  2️⃣ SCHEDULER (Kernel)                                          │
│     File: app/Console/Kernel.php                               │
│     │                                                            │
│     ├─ Frequency: Setiap hari (daily)                          │
│     ├─ Time: Pukul 00:30 (jam 12:30 pagi)                     │
│     ├─ Config: withoutOverlapping() - Cegah double run       │
│     └─ Status: ✅ SELESAI & TESTED                             │
│                                                                 │
│  3️⃣ DATABASE (Migration)                                        │
│     File: database/migrations/2025_11_04_120000_*.php          │
│     │                                                            │
│     ├─ Tabel: deleted_lessons_log                              │
│     ├─ Purpose: Tracking jadwal yang dihapus                   │
│     ├─ Columns: lesson_date, classroom_id, deleted_at, etc    │
│     └─ Status: ✅ EXECUTED & CREATED                           │
│                                                                 │
│  4️⃣ CONTROLLER (Business Logic)                                 │
│     File: app/Http/Controllers/LessonController.php            │
│     │                                                            │
│     ├─ Method 1: showExpiredLessons()                          │
│     │   └─ Tampilkan jadwal yang akan dihapus                  │
│     │                                                            │
│     ├─ Method 2: showDeletedLog()                              │
│     │   └─ Tampilkan history jadwal dihapus                    │
│     │                                                            │
│     ├─ Method 3: destroyManual($id)                            │
│     │   └─ Manual delete jadwal tertentu                       │
│     │                                                            │
│     └─ Status: ✅ SELESAI & TESTED                             │
│                                                                 │
│  5️⃣ VIEWS (UI)                                                   │
│     ├─ File 1: resources/views/lessons/expired.blade.php      │
│     │   └─ UI: Jadwal yang akan dihapus                        │
│     │                                                            │
│     └─ File 2: resources/views/lessons/deleted-log.blade.php  │
│         └─ UI: Log history jadwal dihapus                      │
│         Status: ✅ SELESAI                                     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 ALUR KERJA DETAIL

### **TIMELINE HARIAN**

```
HARI KE-1: GENERATE JADWAL
═════════════════════════════════════════════════════════════════

PUKUL: 08:00 (Siang)
┌───────────────────────────────────────────────────────────────┐
│ ADMIN MEMBUKA FORM GENERATE JADWAL                            │
│ URL: /admin/jadwal/generate                                   │
│                                                                │
│ Form Input:                                                   │
│ ├─ Grade: 11                                                  │
│ ├─ Room Code: A21                                             │
│ ├─ Teacher: Budi                                              │
│ ├─ Subject: Matematika                                        │
│ ├─ Start Date: 01 November 2025                              │
│ ├─ End Date: 30 November 2025  ← 29 hari ke depan!         │
│ ├─ Start Time: 09:00                                          │
│ └─ End Time: 11:00                                            │
│                                                                │
│ SUBMIT FORM                                                   │
└───────────────────────────────────────────────────────────────┘
         ↓
    DATABASE LESSONS TABLE
    ┌─────────────────────────────────────────────────────────┐
    │ INSERT 30 jadwal baru:                                  │
    │ - 2025-11-01, classroom 5, teacher 3                   │
    │ - 2025-11-02, classroom 5, teacher 3                   │
    │ - 2025-11-03, classroom 5, teacher 3                   │
    │ - ... (continue sampai)                                │
    │ - 2025-11-30, classroom 5, teacher 3                   │
    │                                                         │
    │ TOTAL: 30 jadwal aktif                                  │
    └─────────────────────────────────────────────────────────┘

    ✅ SUCCESS MESSAGE:
    "✅ Jadwal berhasil digenerate! 30 jadwal baru dibuat"

═════════════════════════════════════════════════════════════════

HARI KE-2: CLEANUP OTOMATIS (PART 1)
═════════════════════════════════════════════════════════════════

PUKUL: 00:30 (Jam 12:30 pagi)
┌───────────────────────────────────────────────────────────────┐
│ 🔔 SCHEDULER TRIGGERS OTOMATIS (Laravel scheduler)            │
│                                                                │
│ Cron Job Runs:                                                │
│ * * * * * cd /app && php artisan schedule:run                │
│                                                                │
│ Laravel membaca Kernel.php dan menemukan:                     │
│ schedule:cleanup()->daily()->at('00:30')                      │
│                                                                │
│ COMMAND DIJALANKAN: DeleteExpiredLessons                      │
│                                                                │
│ Step 1: Cari jadwal expired                                  │
│   Query: SELECT * FROM lessons                               │
│           WHERE date < '2025-11-02'                          │
│   Result: 1 record (jadwal 2025-11-01)                       │
│                                                                │
│ Step 2: Simpan ke deleted_lessons_log                        │
│   INSERT INTO deleted_lessons_log VALUES (                   │
│     lesson_date: 2025-11-01,                                 │
│     classroom_id: 5,                                         │
│     teacher_id: 3,                                           │
│     deleted_by: 'system',                                    │
│     deleted_at: 2025-11-02 00:30:15,                        │
│     reason: 'Automated cleanup - lesson date has passed'     │
│   )                                                           │
│                                                                │
│ Step 3: Hapus dari lessons table                             │
│   DELETE FROM lessons WHERE id = 1                           │
│                                                                │
│ Step 4: Log ke file                                          │
│   [2025-11-02 00:30:15] INFO DeleteExpiredLessons: Success    │
│   deleted_count: 1                                           │
└───────────────────────────────────────────────────────────────┘
         ↓
    DATABASE STATE AFTER CLEANUP
    ┌─────────────────────────────────────────────────────────┐
    │ LESSONS TABLE: 29 jadwal tersisa                        │
    │ - 2025-11-01: ❌ DIHAPUS                                │
    │ - 2025-11-02: ✅ MASIH ADA                              │
    │ - 2025-11-03: ✅ MASIH ADA                              │
    │ - ... (continue)                                        │
    │ - 2025-11-30: ✅ MASIH ADA                              │
    │                                                         │
    │ DELETED_LESSONS_LOG TABLE: 1 record                     │
    │ - Jadwal 2025-11-01 tercatat di sini                   │
    └─────────────────────────────────────────────────────────┘

    ✅ COMMAND OUTPUT:
    "✅ Cleanup selesai! 1 jadwal dihapus"

═════════════════════════════════════════════════════════════════

HARI KE-3: CLEANUP OTOMATIS (PART 2)
═════════════════════════════════════════════════════════════════

PUKUL: 00:30 (Jam 12:30 pagi)
┌───────────────────────────────────────────────────────────────┐
│ 🔔 SCHEDULER TRIGGERS LAGI                                    │
│                                                                │
│ Query: WHERE date < '2025-11-03'                             │
│ Result: 2 records (01 Nov sudah dihapus, sekarang 02 Nov)    │
│                                                                │
│ Action:                                                       │
│ - Insert 02 Nov ke deleted_lessons_log                      │
│ - Delete 02 Nov dari lessons                                │
│ - Log: "✅ Cleanup selesai! 1 jadwal dihapus"               │
└───────────────────────────────────────────────────────────────┘
         ↓
    DATABASE STATE
    ├─ LESSONS: 28 jadwal (03-30 Nov)
    └─ DELETED_LESSONS_LOG: 2 records (01, 02 Nov)

═════════════════════════════════════════════════════════════════

... (REPEAT SETIAP HARI UNTUK 28 HARI LAGI) ...

═════════════════════════════════════════════════════════════════

HARI KE-30: CLEANUP FINAL
═════════════════════════════════════════════════════════════════

PUKUL: 00:30 (Jam 12:30 pagi)
┌───────────────────────────────────────────────────────────────┐
│ 🔔 SCHEDULER TRIGGERS FINAL TIME                              │
│                                                                │
│ Query: WHERE date < '2025-11-30'                             │
│ Result: 29 records (semua jadwal kecuali yang hari ini)      │
│                                                                │
│ Action:                                                       │
│ - Insert 29 records ke deleted_lessons_log                  │
│ - Delete 29 records dari lessons                            │
│ - Log: "✅ Cleanup selesai! 29 jadwal dihapus"              │
└───────────────────────────────────────────────────────────────┘
         ↓
    FINAL DATABASE STATE
    ├─ LESSONS: 1 jadwal (30 Nov - hari ini)
    └─ DELETED_LESSONS_LOG: 30 records (1-30 Nov)

═════════════════════════════════════════════════════════════════

HARI KE-31: TERAKHIR JADWAL
═════════════════════════════════════════════════════════════════

PUKUL: 00:30 (01 Desember pukul 00:30)
┌───────────────────────────────────────────────────────────────┐
│ 🔔 SCHEDULER TRIGGERS FINAL FINAL TIME                        │
│                                                                │
│ Query: WHERE date < '2025-12-01'                             │
│ Result: 1 record (30 Nov sudah lewat)                        │
│                                                                │
│ Action:                                                       │
│ - Insert 30 Nov ke deleted_lessons_log                      │
│ - Delete 30 Nov dari lessons                                │
│ - Log: "✅ Cleanup selesai! 1 jadwal dihapus"               │
└───────────────────────────────────────────────────────────────┘
         ↓
    FINAL FINAL STATE
    ├─ LESSONS: 0 jadwal (semua dihapus)
    └─ DELETED_LESSONS_LOG: 30 records (ALL history preserved)

═════════════════════════════════════════════════════════════════

KESIMPULAN:
┌───────────────────────────────────────────────────────────────┐
│ ✅ 30 jadwal yang dibuat sehari sebelumnya                    │
│ ✅ Dihapus secara otomatis dalam 30 hari                     │
│ ✅ 1 jadwal dihapus setiap hari pada pukul 00:30             │
│ ✅ Semua data tetap tersimpan di deleted_lessons_log         │
│ ✅ Admin bisa lihat history kapan saja                       │
│ ✅ Zero data loss, 100% automated, fully trackable           │
└───────────────────────────────────────────────────────────────┘
```

---

## 💾 DATABASE STRUCTURE

### **Tabel: lessons (EXISTING)**

```sql
╔════╦════════════╦══════════════╦════════════╦═════════════════╗
║ id ║ date       ║ class_room_id║ teacher_id ║ subject_id      ║
╠════╬════════════╬══════════════╬════════════╬═════════════════╣
║ 1  ║ 2025-11-02 ║ 5            ║ 3          ║ 2               ║ ← Masih ada
║ 2  ║ 2025-11-03 ║ 5            ║ 3          ║ 2               ║ ← Masih ada
║ 3  ║ 2025-11-04 ║ 5            ║ 3          ║ 2               ║ ← Masih ada
╚════╩════════════╩══════════════╩════════════╩═════════════════╝

Note: Jadwal 2025-11-01 DIHAPUS pada 2025-11-02 pukul 00:30
```

### **Tabel: deleted_lessons_log (NEW)**

```sql
╔════╦════════════╦═══════════════╦════════════╦═══════════════════════╦══════════════════╗
║ id ║ lesson_date║ classroom_id  ║ teacher_id ║ deleted_at            ║ deleted_by       ║
╠════╬════════════╬═══════════════╬════════════╬═══════════════════════╬══════════════════╣
║ 1  ║ 2025-11-01 ║ 5             ║ 3          ║ 2025-11-02 00:30:15   ║ system           ║ ← Auto
║ 2  ║ 2025-11-10 ║ 5             ║ 3          ║ 2025-11-15 10:45:32   ║ admin@email.com  ║ ← Manual
║ 3  ║ 2025-11-02 ║ 5             ║ 3          ║ 2025-11-03 00:30:12   ║ system           ║ ← Auto
╚════╩════════════╩═══════════════╩════════════╩═══════════════════════╩══════════════════╝

Note: Semua jadwal yang dihapus tercatat dengan history lengkap
```

---

## 🎮 USER INTERFACE

### **Page 1: Jadwal yang Akan Dihapus** (`/admin/jadwal/will-delete`)

```
┌──────────────────────────────────────────────────────────────────┐
│ ⏰ Jadwal yang Akan Dihapus                                       │
│ Jadwal dengan tanggal yang sudah lewat akan dihapus...           │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│ ⚠️ 15 jadwal akan dihapus dalam cleanup berikutnya              │
│    Cleanup otomatis: Setiap hari pukul 00:30                    │
│                                                                  │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│ Table:                                                           │
│ ┌─────────────┬──────────┬────────┬────────┬─────────┬──────┐  │
│ │ Tanggal     │ Kelas    │ Guru   │ Materi │ Waktu   │ Aksi │  │
│ ├─────────────┼──────────┼────────┼────────┼─────────┼──────┤  │
│ │ 01 Nov 2025 │ XI IPA 1 │ Budi   │ MTK    │ 09-11   │🗑️   │  │
│ │ 02 Nov 2025 │ XI IPA 1 │ Budi   │ MTK    │ 09-11   │🗑️   │  │
│ │ 03 Nov 2025 │ XI IPA 1 │ Budi   │ MTK    │ 09-11   │🗑️   │  │
│ │ ...         │ ...      │ ...    │ ...    │ ...     │ ... │  │
│ └─────────────┴──────────┴────────┴────────┴─────────┴──────┘  │
│                                                                  │
│ [📊 Lihat Log] [➕ Generate Jadwal]                             │
└──────────────────────────────────────────────────────────────────┘
```

**Fitur:**

-   Tampilkan jadwal yang akan dihapus
-   Info kapan cleanup akan berjalan (pukul 00:30)
-   Tombol hapus manual untuk setiap jadwal
-   Pagination untuk banyak data

---

### **Page 2: Log Jadwal Dihapus** (`/admin/jadwal/delete-log`)

```
┌──────────────────────────────────────────────────────────────────┐
│ 📊 Log Jadwal yang Dihapus                                       │
│ Riwayat lengkap semua jadwal yang telah dihapus                 │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│ Stats:                                                           │
│ ┌──────────────┬────────────┬─────────────┐                    │
│ │ Total: 345   │ Otomatis   │ Manual      │                    │
│ │ 🗑️           │ ⚙️ 340     │ 👤 5       │                    │
│ └──────────────┴────────────┴─────────────┘                    │
│                                                                  │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│ Table:                                                           │
│ ┌──────────┬───────┬───────┬───────────────┬────────┬──────┐   │
│ │ Tanggal  │ Kelas │ Guru  │ Dihapus Pada  │ Oleh   │ Alasan   │
│ ├──────────┼───────┼───────┼───────────────┼────────┼──────┤   │
│ │ 01 Nov   │ XI-A  │ Budi  │ 02 Nov 00:30  │ ⚙️ Sys │ Auto ..  │
│ │ 15 Nov   │ XI-B  │ Ani   │ 15 Nov 10:45  │ admin  │ Manual   │
│ │ 02 Nov   │ XI-A  │ Budi  │ 03 Nov 00:30  │ ⚙️ Sys │ Auto ..  │
│ └──────────┴───────┴───────┴───────────────┴────────┴──────┘   │
│                                                                  │
│ [⏰ Lihat akan Dihapus] [➕ Generate Jadwal]                    │
└──────────────────────────────────────────────────────────────────┘
```

**Fitur:**

-   Stats: Total, Auto, Manual deleted count
-   Complete history table
-   Filter & search capability
-   Pagination
-   Audit trail lengkap

---

## ⚙️ KONFIGURASI

### **Mengubah Waktu Cleanup**

File: `app/Console/Kernel.php`

```php
// Default: 00:30 (jam 12:30 pagi)
$schedule->command('schedule:cleanup')->daily()->at('00:30');

// UBAH KE:
$schedule->command('schedule:cleanup')->daily()->at('06:00');  // 6 pagi
// atau
$schedule->command('schedule:cleanup')->daily()->at('22:00');  // 10 malam
```

---

### **Mengubah Kondisi Deletion**

File: `app/Console/Commands/DeleteExpiredLessons.php`

```php
// SAAT INI: Hapus jadwal dengan date < hari ini
$expiredLessons = Lesson::where('date', '<', $today->toDateString())->get();

// UBAH KE (Contoh):

// Hapus jadwal lebih dari 7 hari yang lalu
$sevenDaysAgo = Carbon::now()->subDays(7)->startOfDay();
$expiredLessons = Lesson::where('date', '<', $sevenDaysAgo->toDateString())->get();

// atau Hapus jadwal lebih dari 30 hari yang lalu
$thirtyDaysAgo = Carbon::now()->subDays(30)->startOfDay();
$expiredLessons = Lesson::where('date', '<', $thirtyDaysAgo->toDateString())->get();
```

---

## 🧪 TESTING

### **Test 1: Verify Command Exists**

```bash
php artisan list | grep schedule:cleanup
# Output: schedule:cleanup   Hapus jadwal yang sudah lewat
```

### **Test 2: Run Command Manually**

```bash
php artisan schedule:cleanup
# Output: "✅ Cleanup selesai! X jadwal dihapus" atau "✓ Tidak ada jadwal"
```

### **Test 3: Check Database**

```bash
php artisan tinker
>>> DB::table('deleted_lessons_log')->count()
# Output: Number of deleted records
```

### **Test 4: Access Pages**

```
Browser 1: http://localhost:8000/admin/jadwal/will-delete
Browser 2: http://localhost:8000/admin/jadwal/delete-log
```

### **Test 5: Manual Delete**

-   Go to `/admin/jadwal/will-delete`
-   Click 🗑️ button
-   Confirm delete
-   Verify in `/admin/jadwal/delete-log` with user email

---

## 🚀 PRODUCTION DEPLOYMENT

### **Step 1: Deploy Code**

```bash
git pull origin main  # atau rsync, atau git clone
```

### **Step 2: Run Migration**

```bash
php artisan migrate
# Output: ✓ create_deleted_lessons_log_table DONE
```

### **Step 3: Add Cron Job**

```bash
crontab -e

# Add:
* * * * * cd /var/www/alwi-college && php artisan schedule:run >> /dev/null 2>&1

# Save & Exit
```

### **Step 4: Verify Cron**

```bash
crontab -l | grep schedule:run
# Output: * * * * * cd /var/www/...
```

### **Step 5: Monitor Logs**

```bash
tail -f /var/www/alwi-college/storage/logs/laravel.log | grep schedule
```

---

## 📝 SUMMARY

| Aspek                  | Detail                       |
| ---------------------- | ---------------------------- |
| **Frekuensi**          | Setiap hari pukul 00:30      |
| **Jadwal Dihapus**     | Yang tanggalnya sudah lewat  |
| **Jadwal Bisa Dibuat** | Jauh ke depan (minggu/bulan) |
| **Data Backup**        | deleted_lessons_log table    |
| **Manual Delete**      | Admin bisa delete kapan saja |
| **Monitoring**         | 2 halaman UI untuk tracking  |
| **Logs**               | File log + database logging  |
| **Production**         | Setup cron job (1x saja)     |

---

**✅ SISTEM SELESAI & SIAP PAKAI**

_Untuk lebih detail, baca:_

-   `SISTEM_PENGHAPUSAN_JADWAL.md` - Dokumentasi lengkap 50+ halaman
-   `SETUP_CHECKLIST.md` - Checklist setup step-by-step
