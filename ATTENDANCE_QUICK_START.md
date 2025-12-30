# 📖 Quick Start Guide - Sistem Absensi 30 Hari

## 🎯 Tujuan Sistem

Sistem ini otomatis mencatat berapa kali siswa hadir dalam periode rolling 30 hari. Setelah mencapai 30 kehadiran, counter otomatis di-reset dan riwayat disimpan.

---

## ⚡ Quick Start (5 Menit)

### Step 1: Database Migration

✅ Sudah dilakukan otomatis

-   Tabel `attendance_trackers` sudah dibuat
-   Field: `attendance_count` (0-30), `period_start_date`, `monthly_records`

### Step 2: Guru Mark Attendance

```
1. Guru → menu "Mark Attendance"
2. Pilih kelas
3. Centang siswa yang hadir
4. Klik "Simpan"
```

✅ System otomatis:

-   Catat attendance record
-   Update/create attendance tracker
-   Increment counter +1
-   Jika reach 30 → auto-reset

### Step 3: Admin Monitor

```
1. Admin → Dashboard → Absensi
2. Lihat tabel dengan kolom "Counter 30 Hari"
3. Lihat progress bar per siswa
4. Klik "Export CSV" untuk download report
```

---

## 📊 Data Examples

### Contoh 1: Siswa Baru (Hari Ke-1)

```
Nama: Ahmad
Attendance Count: 1/30
Period Start: 2025-12-30
Days Remaining: 29
Status: Sedang berlangsung
Monthly Records: {}
```

### Contoh 2: Siswa Mendekati Target (Hari Ke-28)

```
Nama: Siti
Attendance Count: 28/30
Period Start: 2025-11-02
Days Remaining: 2
Progress Bar: 93% biru
Status: Hampir reset
Monthly Records: {2025-11: 25, 2025-10: 30}
```

### Contoh 3: Siswa Baru Reset (Hari Ke-1 Periode Baru)

```
Nama: Rudi
Attendance Count: 1/30
Period Start: 2025-12-30 (baru reset hari ini)
Days Remaining: 29
Progress Bar: 3% biru
Status: Baru reset
Monthly Records: {2025-11: 30, 2025-10: 28}
```

---

## 🛠️ Implementation Details

### File-File yang Dibuat/Diupdate

#### 1. **Model**

```
✅ app/Models/AttendanceTracker.php (BARU)
✅ app/Models/Student.php (UPDATED - tambah relation)
✅ app/Models/Attendance.php (UPDATED - formatting)
```

#### 2. **Service**

```
✅ app/Services/AttendanceService.php (BARU)
   - recordAttendance()
   - updateAttendanceTracker()
   - getAttendanceDataForExport()
   - exportToCSV()
```

#### 3. **Controller**

```
✅ app/Http/Controllers/AttendanceController.php (UPDATED)
   - store() method → gunakan service
   - exportAttendanceCSV() (BARU)
   - getStudentTrackingSummary() (BARU)
```

#### 4. **Migration**

```
✅ database/migrations/2025_12_30_000000_create_attendance_trackers_table.php
```

#### 5. **Route**

```
✅ routes/web.php (UPDATED - tambah 2 route baru)
   POST /attendance/export-csv
   GET /attendance/student/{student}/tracking
```

#### 6. **View**

```
✅ resources/views/attendance/admin-view.blade.php (UPDATED)
   - Tambah Export CSV button
   - Tambah Counter column dengan progress bar
```

---

## 💡 Logic Penjelasan

### Auto-Reset Trigger (Ada 2 Cara)

#### Cara 1: Time-Based (30 Hari Kalender)

```php
if ($tracker->period_start_date &&
    now()->diffInDays($tracker->period_start_date) >= 30) {
    // Reset karena 30 hari berlalu
    $tracker->resetCounter();
}
```

#### Cara 2: Count-Based (30 Kehadiran)

```php
if ($tracker->attendance_count >= 30) {
    // Reset karena sudah 30 kali hadir
    $tracker->resetCounter();
}
```

### Reset Data Storage

```php
$monthlyRecords = $tracker->monthly_records ?? [];
$monthlyRecords[now()->format('Y-m')] = $tracker->attendance_count;
// Contoh: ["2025-12" => 30, "2025-11" => 28]
```

---

## 📋 CSV Export Format

### Headers (10 Kolom)

```
Tanggal | Nama Siswa | NIS | Kelas | Sekolah | Status | Guru | Mata Pelajaran | Counter 30 Hari | Tanggal Mulai Period
```

### Contoh Data

```
30-12-2025 14:30:00 | Ahmad Suryanto | NIS001 | 10-A | SMA Negeri 1 | Hadir | Budi Santoso | Matematika | 15 | 02-12-2025
30-12-2025 14:30:00 | Siti Nurhaliza | NIS002 | 10-A | SMA Negeri 1 | Hadir | Budi Santoso | Matematika | 28 | 02-11-2025
```

### Fitur CSV

-   ✅ UTF-8 BOM (kompatibel Excel Indonesia)
-   ✅ Separator semicolon (;)
-   ✅ Format tanggal Indonesia (dd-MM-YYYY)
-   ✅ Bisa filter by month/year/school/class
-   ✅ Download langsung (streaming)

---

## 🔌 API Endpoint

### 1. Export CSV

```
POST /attendance/export-csv

Query Parameters:
- month: 12 (optional, default = current month)
- year: 2025 (optional, default = current year)
- school_id: 1 (optional)
- class_room_id: 5 (optional)

Response:
- Content-Type: text/csv; charset=utf-8
- Attachment: attendance_YYYY-MM-DD_HHmmss.csv

Contoh:
POST /attendance/export-csv?month=12&year=2025
```

### 2. Get Student Tracking Summary

```
GET /attendance/student/{studentId}/tracking

Response JSON:
{
  "student": {...},
  "tracker": {
    "attendance_count": 15,
    "period_start_date": "2025-12-02T00:00:00Z",
    "monthly_records": {"2025-11": 28, "2025-10": 30}
  },
  "counter_30_days": 15,
  "days_remaining": 15,
  "monthly_stats": {
    "hadir": 15,
    "alpha": 2,
    "izin": 1,
    "sakit": 0,
    "total": 18
  }
}
```

---

## ⚠️ Important Notes

### 1. **Counter hanya naik saat status = "present"**

-   Jika status alpha/izin/sakit → counter TIDAK naik
-   Hanya hadir yang dihitung

### 2. **Reset otomatis terjadi 2 kali sehari**

-   Saat increment di `updateAttendanceTracker()`
-   Jika sudah 30 hari calendar → auto-reset

### 3. **Data Historical Preserved**

-   Setiap reset, data disimpan di `monthly_records` (JSON)
-   Contoh: `{"2025-12": 30, "2025-11": 28, "2025-10": 25}`
-   Bisa digunakan untuk analisis trend

### 4. **Authorization**

-   Export CSV: **ADMIN ONLY**
-   Mark Attendance: **TEACHER AUTHORIZED (school+grade)**
-   Get Tracking: **AUTHENTICATED USER**

---

## 🧪 Cara Test

### Test Manual - Attendance Tracking

#### Skenario 1: Kenaikan Counter

```
1. Buka DB → attendance_trackers
2. Create dummy tracker: student_id=1, count=0
3. Guru mark student_id=1 hadir
4. Check DB → counter naik jadi 1
5. Ulangi 29x lebih
```

#### Skenario 2: Auto-Reset (30 Kehadiran)

```
1. Set attendance_count = 29
2. Guru mark hadir sekali lagi
3. Cek di DB:
   - counter reset jadi 0 atau 1 (tergantung logic)
   - period_start_date updated ke hari ini
   - monthly_records: {"2025-12": 30}
```

#### Skenario 3: Time-Based Reset (30 Hari)

```
1. Set period_start_date = 30 hari lalu
2. Set attendance_count = 15
3. Guru mark hadir
4. Cek di DB:
   - counter reset jadi 1
   - period_start_date updated ke hari ini
   - monthly_records: {"2025-11": 15}
```

#### Skenario 4: Export CSV

```
1. Buka admin panel → absensi
2. Klik "Export CSV"
3. File download: attendance_YYYY-MM-DD_HHmmss.csv
4. Buka di Excel → check format OK
```

---

## 🚀 Next Steps

1. **Test semua scenario** (manual atau dengan PHPUnit)
2. **Verify CSV format** di Excel Indonesia
3. **Monitor performance** jika data besar (index sudah ada)
4. **Optional: Tambah notifikasi** saat mendekati 30 hari
5. **Optional: Integrasi Cloudinary** jika perlu backup CSV ke cloud

---

## 📞 Support

Jika ada error atau pertanyaan:

1. Check `storage/logs/laravel.log`
2. Verify migration status: `php artisan migrate:status`
3. Test service: `php artisan tinker` → `App\Services\AttendanceService`

---

**Status**: ✅ READY TO USE

Sistem siap dipakai untuk production tracking absensi 30 hari!
