# 🎓 Panduan Lengkap Dashboard Siswa - Alwi College

## 📸 Tampilan dan Fitur

### 1. **CAROUSEL BANNER** (Bagian Atas Halaman)

Carousel otomatis dengan 3 slide inspiratif yang berisi:

-   Slide 1: "Selamat Datang di Alwi College"
-   Slide 2: "Raih Prestasi Terbaik"
-   Slide 3: "Bergabunglah dengan Komunitas"

**Fitur Interaktif:**
✅ Auto-rotate setiap 5 detik
✅ Pause otomatis saat mouse hover
✅ Tombol navigasi (< dan >)
✅ Dot indicators untuk jump ke slide tertentu
✅ Keyboard navigation (arrow keys)

---

### 2. **NOTIFIKASI PEMBAYARAN** (Alert Box)

#### Jika Belum Membayar Bulan Ini:

```
🔴 Alert Merah - Pembayaran Belum Lunas
"Anda belum melakukan pembayaran untuk bulan [Bulan Saat Ini]
Silakan segera upload bukti pembayaran Anda"
→ Tombol: Upload Bukti Pembayaran
```

#### Jika Sudah Membayar Bulan Ini:

```
🟢 Alert Hijau - Pembayaran Sudah Lunas
"Terima kasih telah melunasi pembayaran untuk bulan [Bulan Saat Ini]"
```

**Logika Sistem:**

-   Mengecek pembayaran dengan status `approved` untuk bulan sekarang
-   Format bulan disimpan sebagai `MM-YYYY` (contoh: `10-2025`)
-   Update otomatis setiap kali halaman refresh

---

### 3. **KARTU INFORMASI BIMBINGAN** (3 Cards)

#### Card 1: Bimbingan Akademik 🎯

-   Icon: Target/Graph
-   Warna: Biru
-   Teks: "Dapatkan dukungan penuh dari tim pengajar berpengalaman..."
-   Link: Pelajari Lebih Lanjut

#### Card 2: Monitoring Nilai 📊

-   Icon: Bar Chart
-   Warna: Hijau
-   Teks: "Pantau perkembangan nilai akademik Anda secara real-time..."
-   Link: Lihat Nilai Saya

#### Card 3: Beasiswa & Program 💰

-   Icon: Coins
-   Warna: Purple
-   Teks: "Jangan lewatkan berbagai program beasiswa..."
-   Link: Cek Ketersediaan

**Responsive:**

-   Mobile: 1 kolom (card penuh width)
-   Tablet/Desktop: 3 kolom (spread horizontal)

---

### 4. **SECTION "TENTANG KAMI"** (Large Card)

Layout 2 kolom:

#### Kolom Kiri: Gambar Placeholder

-   Gradient background (abu-abu)
-   Icon gambar di tengah
-   Dimensi: Square (aspect-ratio 1:1)
-   Mudah untuk diganti dengan gambar real sekolah

#### Kolom Kanan: Informasi Sekolah

**Judul:** "Tentang Kami"

**Paragraf 1:**
"Alwi College didirikan pada tahun 2023, berawal dari sebuah visi sederhana
untuk memberikan pendidikan berkualitas tinggi kepada masyarakat..."

**Paragraf 2:**
"Kami percaya bahwa setiap siswa memiliki potensi unik untuk berkembang.
Oleh karena itu, Alwi College menyediakan lingkungan belajar yang mendukung..."

**Statistik (3 Kolom):**

-   👥 **[Jumlah]** Siswa Aktif ← dari database
-   👨‍🏫 **[Jumlah]** Pengajar Berpengalaman ← dari database
-   📅 **2023** Berdiri Sejak ← fixed value

---

### 5. **QUICK ACCESS CARDS** (2 Buttons)

#### Button 1: Unggah Info / Kisi-kisi

-   Gradient: Biru
-   Icon: Plus/Upload
-   Teks: "Bagikan materi pembelajaran dengan pengajar"
-   Link: `/info` (info.index)

#### Button 2: Riwayat Pembayaran

-   Gradient: Hijau
-   Icon: Checkmark
-   Teks: "Lihat semua transaksi pembayaran Anda"
-   Link: `/payment` (pay.index)

**Hover Effect:**

-   Icon bergerak ke kanan
-   Shadow meningkat
-   Card sedikit naik

---

## 🔧 Kustomisasi

### A. Mengubah Teks "Tentang Kami"

File: `resources/views/dashboard/student.blade.php`

Cari bagian (sekitar line 280):

```blade
<h2 class="text-3xl font-bold text-gray-900 mb-4">Tentang Kami</h2>
<p class="text-gray-600 leading-relaxed mb-4">
  <!-- GANTI TEKS INI -->
  Alwi College didirikan pada tahun 2023...
</p>
<p class="text-gray-600 leading-relaxed mb-6">
  <!-- GANTI TEKS INI -->
  Kami percaya bahwa setiap siswa memiliki potensi...
</p>
```

### B. Menambah Gambar Sekolah

Tempat gambar di: `public/images/`

1. Copy gambar ke: `public/images/sekolah.jpg`
2. Edit file `resources/views/dashboard/student.blade.php`
3. Cari div `about-image-placeholder` (sekitar line 250)
4. Ganti dengan:

```blade
<div class="relative w-full max-w-md">
  <img src="{{ asset('images/sekolah.jpg') }}"
       alt="Alwi College"
       class="w-full h-full object-cover rounded-2xl shadow-lg">
</div>
```

### C. Mengubah Warna Card Bimbingan

Contoh untuk Card 1 (Bimbingan Akademik):

Cari bagian:

```blade
<div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100">
  <svg class="h-6 w-6 text-blue-600" ...>
```

Ubah warna (contoh ubah ke merah):

```blade
<div class="flex items-center justify-center h-12 w-12 rounded-lg bg-red-100">
  <svg class="h-6 w-6 text-red-600" ...>
```

Warna Tailwind yang bisa digunakan:

-   blue, red, green, purple, pink, yellow, orange, indigo, cyan, dll

### D. Mengubah Teks Card Bimbingan

Cari bagian text masing-masing card, contoh:

```blade
<p class="text-gray-600 text-sm leading-relaxed">
  Dapatkan dukungan penuh dari tim pengajar berpengalaman...
</p>
```

### E. Menambah/Mengurangi Slide Carousel

1. Tambah slide HTML:

```blade
<div class="carousel-slide absolute w-full h-full transition-opacity duration-1000 opacity-0" data-index="3">
  <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[COLOR] to-[COLOR]">
    <div class="text-center text-white px-8">
      <h3 class="text-3xl md:text-4xl font-bold mb-4">JUDUL</h3>
      <p class="text-lg md:text-xl">DESKRIPSI</p>
    </div>
  </div>
</div>
```

2. Tambah dot navigation:

```blade
<button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50 cursor-pointer transition-opacity hover:opacity-75" data-index="3"></button>
```

---

## 📊 Database Schema yang Diperlukan

### Tabel: `payments`

```sql
- id (int, primary key)
- student_id (int, foreign key)
- month_period (varchar, format: MM-YYYY)
  Contoh: "10-2025", "09-2025"
- amount (decimal)
- proof_path (varchar)
- status (enum: approved, pending, rejected)
- note (text, nullable)
- created_at, updated_at (timestamp)
```

### Tabel: `students`

```sql
- id (int, primary key)
- user_id (int, foreign key)
- class_room_id (int, foreign key)
- nis (varchar, unique)
- created_at, updated_at (timestamp)
```

### Tabel: `teachers`

```sql
- id (int, primary key)
- user_id (int, foreign key)
- nip (varchar, unique)
- phone (varchar, nullable)
- created_at, updated_at (timestamp)
```

---

## 🎨 Warna dan Styling

### Color Scheme

| Elemen             | Warna  | Tailwind Class         |
| ------------------ | ------ | ---------------------- |
| Bimbingan Akademik | Biru   | blue-100, blue-600     |
| Monitoring Nilai   | Hijau  | green-100, green-600   |
| Beasiswa           | Purple | purple-100, purple-600 |
| Pembayaran OK      | Hijau  | green-50, green-500    |
| Pembayaran Belum   | Merah  | red-50, red-500        |

### Responsive Breakpoints

```
Mobile (< 768px):  Single column, full width
Tablet (768px+):   2-3 columns
Desktop (1024px+): Full multi-column layout
```

---

## 🚀 Testing Checklist

Sebelum push ke production, pastikan:

-   [ ] **Carousel**

    -   [ ] Auto-rotate setiap 5 detik
    -   [ ] Tombol prev/next berfungsi
    -   [ ] Dot navigation berfungsi
    -   [ ] Pause saat hover
    -   [ ] Resume setelah mouse leave
    -   [ ] Keyboard navigation (arrow keys) bekerja

-   [ ] **Notifikasi Pembayaran**

    -   [ ] Menampilkan alert merah jika belum bayar bulan ini
    -   [ ] Menampilkan alert hijau jika sudah bayar bulan ini
    -   [ ] Tombol "Upload Bukti" link ke halaman payment
    -   [ ] Update otomatis setelah refresh halaman

-   [ ] **Cards Bimbingan**

    -   [ ] 3 card tampil dengan proper
    -   [ ] Responsive di mobile
    -   [ ] Icon dan warna tepat
    -   [ ] Link berfungsi (atau dikonfigurasi)

-   [ ] **Tentang Kami Section**

    -   [ ] Layout 2 kolom tampil di desktop
    -   [ ] Layout 1 kolom di mobile
    -   [ ] Statistik menampilkan angka dari database
    -   [ ] Gambar placeholder tampil dengan benar

-   [ ] **Quick Access**

    -   [ ] 2 button tampil di bawah
    -   [ ] Gradient bekerja
    -   [ ] Hover effect bekerja
    -   [ ] Link ke info.index dan pay.index berfungsi

-   [ ] **Overall**
    -   [ ] Responsive di semua ukuran layar
    -   [ ] Tidak ada error di console browser
    -   [ ] Font dan spacing sesuai
    -   [ ] Warna sesuai dengan brand

---

## 📝 File-File yang Diubah

1. **`resources/views/dashboard/student.blade.php`**

    - Replaced seluruh dashboard layout
    - Menambah carousel, alerts, cards

2. **`app/Http/Controllers/DashboardController.php`**

    - Update method `student()` untuk pass $payments, $totalStudents, $totalTeachers

3. **`resources/js/app.js`**
    - Import carousel.js

## 📄 File-File Baru yang Dibuat

1. **`resources/js/carousel.js`**

    - Class CarouselController untuk handle carousel logic
    - Auto-rotate, manual control, keyboard navigation

2. **`resources/css/dashboard.css`**

    - Styling tambahan untuk carousel dan cards

3. **`DASHBOARD_STUDENT_UPDATES.md`**
    - Dokumentasi teknis lengkap

---

## 🐛 Troubleshooting

### Carousel tidak bergerak

**Solusi:**

```javascript
// Di browser console, cek:
console.log(window.carousel); // Harus ada object CarouselController

// Pastikan JS sudah di-load:
// Lihat di Network tab, pastikan app-*.js ada
```

### Notifikasi pembayaran tidak tampil

**Solusi:**

```php
// Di dashboard/student.blade.php, cek query:
dd($payments); // Lihat struktur data

// Pastikan database column:
// - month_period format MM-YYYY
// - status ada value 'approved'
```

### Warna tidak sesuai

**Solusi:**

-   Build ulang assets: `npm run build`
-   Clear cache: `php artisan view:clear`
-   Hard refresh browser: `Ctrl+Shift+R` atau `Cmd+Shift+R`

### Gambar placeholder tidak tampil

**Solusi:**

-   Pastikan path gambar benar di asset folder
-   Gunakan `{{ asset('path/to/image') }}`
-   Build ulang: `npm run build`

---

## 💡 Tips & Tricks

1. **Membuat slide baru dengan cepat**

    - Copy-paste slide div
    - Update `data-index` value
    - Update warna gradient
    - Update teks

2. **Menggunakan gambar lokal**

    - Upload ke: `public/images/`
    - Reference: `{{ asset('images/nama.jpg') }}`

3. **Styling custom dengan Tailwind**

    - Semua class menggunakan Tailwind
    - Combine classes untuk custom look
    - Contoh: `bg-blue-600 hover:bg-blue-700 transition`

4. **Menambah link dinamis**
    - Ganti `#` dengan `{{ route('route_name') }}`
    - Contoh: `href="{{ route('lessons.index') }}"`

---

## 📞 Support & Questions

Jika ada yang kurang jelas atau perlu ditambahkan:

1. Periksa file dokumentasi yang ada
2. Buka `resources/views/dashboard/student.blade.php` untuk lihat kode
3. Lihat controller di `app/Http/Controllers/DashboardController.php`
4. Check browser console untuk error messages

---

**Last Updated:** October 17, 2025
**Status:** ✅ Production Ready
