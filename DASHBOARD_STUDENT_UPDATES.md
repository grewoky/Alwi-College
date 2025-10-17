# Dashboard Siswa - Update Documentation

## 📋 Fitur-Fitur yang Ditambahkan

### 1. **Carousel Banner** (Bagian Atas)

-   **3 Slide Otomatis** dengan konten inspiratif
-   **Auto-rotate setiap 5 detik** di background
-   **Navigation buttons** (prev/next) untuk manual control
-   **Dot indicators** untuk melihat posisi slide saat ini
-   **Hover pause** - carousel berhenti saat mouse hover
-   **Responsive design** untuk mobile dan desktop

**Fitur Interaktif:**

-   Klik dot untuk langsung ke slide tertentu
-   Klik arrow untuk navigasi prev/next
-   Pause saat hover, resume saat mouse leave

---

### 2. **Payment Notification Alert** (Setelah Carousel)

-   **Sistem otomatis** yang mengecek pembayaran bulan berjalan
-   **2 Status Notifikasi:**
    -   **Merah (Belum Bayar)**: Menampilkan alert jika belum ada pembayaran approved untuk bulan saat ini
    -   **Hijau (Sudah Bayar)**: Menampilkan konfirmasi sukses jika sudah lunas

**Logika:**

```javascript
- Ambil bulan dan tahun saat ini (format: MM-YYYY)
- Cek apakah ada payment dengan status 'approved' untuk month_period tersebut
- Jika tidak ada: tampilkan alert merah dengan tombol upload
- Jika ada: tampilkan pesan sukses hijau
```

**Database Schema yang digunakan:**

-   `payments.month_period` (format: MM-YYYY)
-   `payments.status` (approved/pending/rejected)
-   `payments.student_id`

---

### 3. **Info Bimbingan Cards** (3 Kartu Informasi)

Tiga kartu dengan ikon dan deskripsi tentang layanan bimbingan:

1. **Bimbingan Akademik** (Ikon: Graph/Target)

    - Deskripsi tentang dukungan pengajar
    - Link untuk pelajari lebih lanjut

2. **Monitoring Nilai** (Ikon: Bar Chart)

    - Deskripsi tentang pantau nilai akademik
    - Link ke halaman nilai

3. **Beasiswa & Program** (Ikon: Coins)
    - Deskripsi tentang program beasiswa
    - Link ke halaman beasiswa

**Features:**

-   Card dengan shadow dan hover effect
-   Icon berwarna dengan background color
-   Responsive grid (1 kolom mobile, 3 kolom desktop)

---

### 4. **Tentang Kami Section** (Card Utama)

Layout 2 kolom dengan image placeholder di kiri dan teks di kanan:

**Kolom Kiri:**

-   Image placeholder dengan gradient background
-   Icon placeholder untuk gambar sekolah
-   Dimensi responsif

**Kolom Kanan:**

-   Judul "Tentang Kami"
-   2 Paragraf deskripsi sekolah
-   Statistik di bawah dengan 3 data:
    -   Jumlah Siswa Aktif (dari database)
    -   Jumlah Pengajar Berpengalaman (dari database)
    -   Tahun Berdiri (2023)

**Tips Kustomisasi:**

-   Ganti placeholder dengan gambar real: Buka `resources/views/dashboard/student.blade.php`
-   Cari `<img class="w-full h-full object-cover rounded-2xl" />`
-   Ganti `src="IMAGE_PATH"` dengan path gambar Anda

---

### 5. **Quick Access Cards** (Tombol Cepat)

2 tombol gradient di bagian paling bawah:

1. **Unggah Info / Kisi-kisi** (Gradient Biru)

    - Tombol upload untuk berbagi materi dengan pengajar
    - Route: `route('info.index')`

2. **Riwayat Pembayaran** (Gradient Hijau)
    - Tombol untuk melihat riwayat pembayaran
    - Route: `route('pay.index')`

**Features:**

-   Gradient background yang menarik
-   Icon dengan hover animation
-   Full responsive

---

## 🔧 File yang Dimodifikasi

### 1. `resources/views/dashboard/student.blade.php`

**Perubahan:**

-   Hapus layout lama sederhana
-   Tambah carousel dengan 3 slide
-   Tambah notifikasi pembayaran dinamis
-   Tambah 3 kartu bimbingan
-   Tambah section "Tentang Kami" dengan statistik
-   Tambah quick access cards
-   Include JavaScript untuk carousel functionality

**New Variables yang Dibutuhkan:**

-   `$presentCount` - Total kehadiran siswa
-   `$lastPayment` - Pembayaran terakhir
-   `$payments` - Semua pembayaran siswa (untuk checking pembayaran bulan ini)
-   `$totalStudents` - Total siswa untuk statistik
-   `$totalTeachers` - Total guru untuk statistik

### 2. `app/Http/Controllers/DashboardController.php`

**Perubahan di method `student()`:**

```php
// Tambahan baru:
$payments = \App\Models\Payment::where('student_id',$student->id)->get();
$totalStudents = \App\Models\Student::count();
$totalTeachers = \App\Models\Teacher::count();

// Passing ke view:
compact('presentCount','lastPayment','payments','totalStudents','totalTeachers')
```

---

## 💡 Logika JavaScript Carousel

```javascript
// Auto-advance slides setiap 5 detik
let carouselInterval = setInterval(nextSlide, 5000);

// Click handlers untuk navigation
- .carousel-next: Maju ke slide berikutnya
- .carousel-prev: Mundur ke slide sebelumnya
- .carousel-dot: Jump ke slide tertentu

// Pause pada hover, resume saat mouse leave

// Toggle opacity untuk animasi transisi slides
```

---

## 🎨 Styling & CSS Classes

-   **Tailwind CSS** untuk semua styling
-   **Responsive breakpoints:**
    -   Mobile: Single column layout
    -   Tablet/Desktop: Multi-column grid
-   **Color Scheme:**
    -   Blue: Bimbingan Akademik
    -   Green: Monitoring Nilai, Pembayaran
    -   Purple: Beasiswa

---

## 📝 How to Customize

### Mengubah Text Tentang Kami:

```blade
<!-- File: resources/views/dashboard/student.blade.php, line ~280 -->
<h2 class="text-3xl font-bold text-gray-900 mb-4">Tentang Kami</h2>
<p class="text-gray-600 leading-relaxed mb-4">
  <!-- Ganti teks ini dengan deskripsi sekolah Anda -->
</p>
```

### Menambah Gambar Real:

```blade
<!-- Ganti placeholder div ini dengan image tag -->
<img class="w-full h-full object-cover rounded-2xl"
     src="{{ asset('images/sekolah.jpg') }}"
     alt="Sekolah Alwi College">
```

### Mengubah Slide Carousel:

-   Tambah atau kurangi `.carousel-slide` div
-   Update jumlah `.carousel-dot` button sesuai slides
-   Update `totalSlides` variable di JavaScript

### Mengubah Warna Card:

Ganti class color di info cards:

-   `bg-blue-100` → `bg-red-100`
-   `text-blue-600` → `text-red-600`
-   dll

---

## ✅ Testing Checklist

-   [ ] Carousel auto-rotate setiap 5 detik
-   [ ] Tombol next/prev bekerja
-   [ ] Dot navigation bekerja
-   [ ] Hover pause carousel berfungsi
-   [ ] Notifikasi pembayaran tampil sesuai logika
-   [ ] Statistik "Tentang Kami" mengambil data dari database
-   [ ] Responsive design di mobile dan desktop
-   [ ] Semua link navigasi berfungsi
-   [ ] Warna dan styling sesuai dengan tema

---

## 🚀 Deployment Notes

1. Pastikan database sudah di-migrate
2. Build assets: `npm run build`
3. Clear cache jika perlu: `php artisan cache:clear`
4. Test di browser: http://localhost:8000

---

## 📞 Support

Jika ada yang perlu disesuaikan lebih lanjut:

1. Edit template di `resources/views/dashboard/student.blade.php`
2. Update controller di `app/Http/Controllers/DashboardController.php`
3. Clear cache dengan `php artisan view:clear`
4. Refresh browser untuk melihat perubahan
