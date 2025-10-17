# ⚡ QUICK START GUIDE - FITUR BARU ALWI COLLEGE

**Last Updated**: October 17, 2025  
**Status**: ✅ LIVE & WORKING

---

## 🎯 APA YANG BARU?

### 1️⃣ **NAVBAR YANG SAMA DI SEMUA HALAMAN**

-   Navbar sekarang konsisten di Dashboard, Jadwal, Info, dan Absensi
-   Menu items: 📊 Dashboard | 📅 Jadwal Les | 📋 Info | ✓ Absensi
-   Responsive (mobile hamburger menu)
-   User name & Logout button

### 2️⃣ **JADWAL LES UNTUK SISWA & ADMIN**

-   Siswa: Lihat jadwal kelas mereka
-   Admin: Lihat & manage semua jadwal
-   Filter berdasarkan tanggal, pengajar, kelas
-   Card view yang cantik & responsive

### 3️⃣ **ABSENSI YANG LEBIH BAIK**

-   **Siswa**: Lihat riwayat absensi mereka dengan statistik
-   **Guru**: Input absensi per siswa per jam pelajaran
-   **Admin**: Lihat ringkasan absensi semua siswa grouped by classroom
-   Classroom grouping: A23, A22, A21, B23, B22, B21 (matrix style)
-   Status badges dengan warna: Hadir ✓ | Tidak ✗ | Izin | Sakit

---

## 🚀 CARA MENGGUNAKAN

### UNTUK SISWA 👤

#### 1. Melihat Jadwal

```
1. Login sebagai Student
2. Click navbar "📅 Jadwal Les"
3. Lihat jadwal kelas Anda
4. Bisa filter berdasarkan tanggal
```

#### 2. Melihat Absensi

```
1. Login sebagai Student
2. Click navbar "✓ Absensi"
3. Lihat riwayat kehadiran Anda
4. Lihat statistik: Hadir | Tidak Hadir | Izin | Sakit
```

---

### UNTUK GURU / TEACHER 👨‍🏫

#### 1. Melihat Jadwal

```
1. Login sebagai Teacher
2. Click navbar "📅 Jadwal Les"
3. Lihat semua jadwal yang Anda ajar
4. Filter berdasarkan kelas atau tanggal
```

#### 2. Input Absensi

```
1. Login sebagai Teacher
2. Click navbar "✓ Absensi"
3. Lihat daftar kelas yang Anda ajar (grouped by Blok A, B, C, dll)
4. Click kelas yang ingin di-absen (contoh: A23)
5. Lihat daftar semua siswa di kelas itu
6. Untuk setiap siswa, pilih status:
   - ✓ Hadir
   - ✗ Tidak Hadir
   - 📋 Izin
   - 🏥 Sakit
7. Click "💾 Simpan Absensi"
8. Selesai! Data absensi tersimpan
```

**Catatan**:

-   Absensi otomatis linked dengan lesson hari ini
-   Trip counter akan terupdate otomatis (sudah ada di sistem)
-   Bisa re-edit absensi kapan saja (akan update otomatis)

---

### UNTUK ADMIN 👨‍💼

#### 1. Melihat Jadwal Semua Kelas

```
1. Login sebagai Admin
2. Click navbar "📅 Jadwal Les"
3. Lihat semua jadwal di semua kelas
4. Filter: Teacher | Kelas | Tanggal
5. Button "➕ Generate Jadwal Baru" untuk buat jadwal baru
```

#### 2. Generate Jadwal Baru (Existing Feature)

```
1. Click "➕ Generate Jadwal Baru"
2. Pilih: Kelas | Mata Pelajaran | Guru | Tanggal Awal | Tanggal Akhir
3. System akan buat jadwal otomatis tiap 2 hari
4. Jadwal akan otomatis dikirim ke guru & siswa
```

#### 3. Melihat Ringkasan Absensi

```
1. Login sebagai Admin
2. Click navbar "✓ Absensi"
3. Lihat ringkasan absensi semua siswa
4. Grouped by Classroom: Blok A (A23, A22, A21) | Blok B (B23, B22, B21)
5. Table untuk setiap kelas:
   - Nama siswa
   - Jumlah Hadir (green badge)
   - Jumlah Tidak Hadir (red badge)
   - Jumlah Izin (yellow badge)
   - Jumlah Sakit (blue badge)
6. Lihat statistik untuk decision making
```

---

## 📋 URL REFERENCE

### Student URLs:

| Feature    | URL                   | Route Name         |
| ---------- | --------------------- | ------------------ |
| Dashboard  | `/dashboard`          | `dashboard`        |
| Jadwal     | `/student-jadwal`     | `lessons.index`    |
| Info       | `/info`               | `info.index`       |
| Absensi    | `/student-attendance` | `attendance.index` |
| Pembayaran | `/payment`            | `pay.index`        |

### Teacher URLs:

| Feature       | URL                     | Route Name           |
| ------------- | ----------------------- | -------------------- |
| Dashboard     | `/dashboard`            | `dashboard`          |
| Jadwal        | `/teacher-jadwal`       | `lessons.index`      |
| Absensi       | `/teacher-attendance`   | `attendance.teacher` |
| Input Absensi | `/mark-attendance/{id}` | `attendance.mark`    |

### Admin URLs:

| Feature         | URL                       | Route Name              |
| --------------- | ------------------------- | ----------------------- |
| Dashboard       | `/dashboard`              | `dashboard`             |
| Jadwal          | `/admin-jadwal`           | `lessons.index`         |
| Generate Jadwal | `/admin/lessons/generate` | `lessons.generate.form` |
| Absensi         | `/admin-attendance`       | `attendance.admin`      |

### Universal Routes (Auto-redirect):

| Feature | URL           | Redirect To                   |
| ------- | ------------- | ----------------------------- |
| Jadwal  | `/jadwal`     | Student/Teacher/Admin version |
| Absensi | `/attendance` | Student/Teacher/Admin version |

---

## 🎨 CLASSROOM GROUPING SYSTEM

Sistem baru mengelompokkan kelas berdasarkan prefix ruangan:

### Format Nama Kelas:

```
A23, A22, A21     ← Blok A (Lantai A atau Zona A)
B23, B22, B21     ← Blok B (Lantai B atau Zona B)
C23, C22, C21     ← Blok C (jika ada)
```

### Contoh di Admin Absensi:

```
Blok A
┌─────────────────────────────────────┐
│ Kelas A23 (35 siswa)               │
│ ┌──────────────────────────────────┐│
│ │ Nama | Hadir | Tidak | Izin | Sakit│
│ ├──────────────────────────────────┤│
│ │ Budi | 15    | 2     | 1    | 0    │
│ │ Ani  | 16    | 1     | 1    | 0    │
│ └──────────────────────────────────┘│
│                                      │
│ Kelas A22 (32 siswa)                │
│ [data...]                            │
└─────────────────────────────────────┘

Blok B
[sama seperti Blok A]
```

---

## 🔄 WORKFLOW ABSENSI

### Workflow Guru Input Absensi:

```
Step 1: Login sebagai Teacher
         ↓
Step 2: Click "✓ Absensi" di navbar
         ↓
Step 3: Lihat daftar kelas (grouped: A23, A22, A21, B23, B22, B21)
         ↓
Step 4: Click kelas yang ingin di-absen (contoh: A23)
         ↓
Step 5: Lihat form dengan daftar 35 siswa di kelas A23
         ↓
Step 6: Untuk setiap siswa:
         - Select radio button: ✓ Hadir / ✗ Tidak / 📋 Izin / 🏥 Sakit
         ↓
Step 7: Click "💾 Simpan Absensi"
         ↓
Step 8: System:
         - Save attendance untuk setiap siswa
         - Link ke lesson hari ini
         - Update trip counter (+1, max 3)
         - Bonus check kalau hari Minggu
         ↓
Step 9: Success! ✓ "Absensi berhasil dicatat!"
```

### Workflow Siswa Lihat Absensi:

```
Step 1: Login sebagai Student
         ↓
Step 2: Click "✓ Absensi" di navbar
         ↓
Step 3: Lihat statistik:
         - Total Hadir: 15 ✓
         - Total Tidak Hadir: 2 ✗
         - Total Izin: 1 📋
         - Total Sakit: 0 🏥
         - Total Sesi: 18 📊
         ↓
Step 4: Scroll down lihat tabel riwayat
         ↓
Step 5: Baca setiap entry:
         - Tanggal: 14 Nov 2024
         - Pelajaran: Matematika
         - Guru: Pak Budi
         - Status: ✓ Hadir (green badge)
         ↓
Step 6: Done! Lihat riwayat kehadiran lengkap
```

---

## 📊 CONTOH DATA

### Contoh Jadwal (Student View):

```
📅 Jadwal Pelajaran - Kelas 10 IPA 1

[Card 1]
Matematika
📅 14 Nov 2024
Pengajar: Pak Budi
Jam: 15:30 - 17:00
Ruang: A23

[Card 2]
Fisika
📅 15 Nov 2024
Pengajar: Pak Adi
Jam: 14:00 - 15:30
Ruang: A23

[Card 3]
Kimia
📅 16 Nov 2024
Pengajar: Bu Siti
Jam: 10:00 - 11:30
Ruang: A22
```

### Contoh Absensi (Admin View):

```
Blok A

Kelas A23 (35 siswa)
| Nama              | ✓ Hadir | ✗ Tidak | 📋 Izin | 🏥 Sakit |
|-------------------|---------|---------|---------|----------|
| Budi Santoso      |   15    |    2    |    1    |    0     |
| Ani Wijaya        |   16    |    1    |    1    |    0     |
| Citra Kusuma      |   18    |    0    |    0    |    0     |
| ... (32 more)     |   ...   |   ...   |   ...   |   ...    |

Kelas A22 (32 siswa)
| Nama              | ✓ Hadir | ✗ Tidak | 📋 Izin | 🏥 Sakit |
|-------------------|---------|---------|---------|----------|
| Deo Pratama       |   17    |    1    |    0    |    1     |
| ... (31 more)     |   ...   |   ...   |   ...   |   ...    |
```

---

## 🐛 TROUBLESHOOTING

### Problem: Navbar tidak muncul

**Solution**:

-   Pastikan `<x-app-navbar />` ada di halaman
-   Run `npm run build` untuk compile assets
-   Clear cache: `php artisan cache:clear`

### Problem: Absensi tidak tersimpan

**Solution**:

-   Pastikan Anda login sebagai Teacher/Admin
-   Pastikan kelas ada di database
-   Check logs: `storage/logs/laravel.log`

### Problem: Classroom grouping tidak benar

**Solution**:

-   Classroom names harus dengan prefix (A23, B22, dll)
-   System otomatis ambil 1 karakter pertama sebagai prefix
-   Check di database: `SELECT * FROM class_rooms;`

### Problem: Filter tidak bekerja

**Solution**:

-   Pastikan dropdown punya data
-   Check di database: `SELECT * FROM teachers;`
-   Try reset filter button

### Problem: Pagination tidak work

**Solution**:

-   Check URL query strings preserved
-   System using `.withQueryString()` di controller
-   Should maintain filter saat pindah page

---

## ✅ CHECKLIST SEBELUM GO-LIVE

-   [ ] Database sudah punya data: Teachers, Students, ClassRooms
-   [ ] ClassRoom names sudah dengan prefix (A23, A22, dst)
-   [ ] All users sudah assign ke roles (student, teacher, admin)
-   [ ] npm run build berhasil (no errors)
-   [ ] Test semua features di browser
-   [ ] Mobile view tested
-   [ ] Test dari 3 roles: student, teacher, admin
-   [ ] Check laravel.log untuk errors
-   [ ] Backup database sebelum deploy

---

## 📞 SUPPORT & QUESTIONS

**Questions?** Refer to:

1. `COMPLETE_FEATURE_DOCUMENTATION.md` - Detailed feature docs
2. `REDESIGN_EXECUTIVE_SUMMARY.md` - Summary of changes
3. Controller files di `app/Http/Controllers/`
4. Model files di `app/Models/`
5. Routes file di `routes/web.php`

**Bugs?**

-   Report dengan screenshot & exact steps to reproduce
-   Include laravel.log errors
-   Include database structure info

---

**Happy using Alwi College v2.0! 🎓**

**Last Build**: ✓ 55 modules transformed in 1.14s  
**Status**: ✅ PRODUCTION READY
