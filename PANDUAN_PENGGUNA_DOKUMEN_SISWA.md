# 👨‍🏫 PANDUAN PENGGUNA - DOKUMEN SISWA (ANAK BANGAU)

---

## 📱 Tampilan Halaman

### **Header:**

```
┌─────────────────────────────────────────────────────────────┐
│ AC  Alwi College                  Dashboard  Jadwal  Absensi │
│                                                        Logout │
└─────────────────────────────────────────────────────────────┘
```

### **Main Content:**

```
📚 Dokumen Siswa
Lihat file yang diupload oleh siswa Anda
```

---

## 🔍 FITUR 1: Filter Kelas

### **Tampilan:**

```
🏫 Kelas (Anak Bangau)
┌─────────────────────────────────────┐
│ ▼ -- Semua Kelas --                 │
│   Kelas 10 - IPA A                  │
│   Kelas 10 - IPS B                  │
│   Kelas 11 - IPA C                  │
│   Kelas 11 - IPS D                  │
│   Kelas 12 - IPA E                  │
│   Kelas 12 - IPS F                  │
└─────────────────────────────────────┘
```

### **Cara Menggunakan:**

1. Klik dropdown "Kelas (Anak Bangau)"
2. Pilih salah satu kelas
3. Data akan otomatis filter menampilkan file dari kelas tersebut

### **Opsi yang Tersedia:**

-   **-- Semua Kelas --** → Tampilkan file dari semua Kelas 10, 11, 12
-   **Kelas 10 - ...** → Tampilkan hanya file dari Kelas 10
-   **Kelas 11 - ...** → Tampilkan hanya file dari Kelas 11
-   **Kelas 12 - ...** → Tampilkan hanya file dari Kelas 12

### **Contoh Penggunaan:**

```
Saya ingin lihat dokumen dari kelas 11 saja
↓
Klik dropdown "Kelas (Anak Bangau)"
↓
Pilih "Kelas 11 - IPA C"
↓
Tabel akan menampilkan hanya file dari Kelas 11 IPA C
↓
Siswa yang ditampilkan: Ahmad, Budi, Citra, Dini (dari kelas 11 IPA C)
```

---

## 📖 FITUR 2: Cari Matapelajaran

### **Tampilan:**

```
📖 Matapelajaran
┌─────────────────────────────────────┐
│ Cari matapelajaran...               │
└─────────────────────────────────────┘
```

### **Cara Menggunakan:**

1. Klik kolom "Matapelajaran"
2. Ketik nama mata pelajaran yang dicari
3. Contoh: `Matematika`, `Bahasa Inggris`, `Fisika`, dll
4. Klik tombol `🔍 Filter`

### **Contoh Pencarian:**

```
Cari: "Matematika"
Hasil: File dengan nama matapelajaran mengandung "Matematika"
- Tugas MTK
- Kisi-Kisi Matematika
- Materi Aljabar (jika ada "Matematika" di field)

Cari: "Inggris"
Hasil: File dengan nama matapelajaran mengandung "Inggris"
- Essay B.Inggris
- Grammar
- Vocabulary
```

---

## 🎯 FITUR 3: Filter (Apply Semua)

### **Tombol:**

```
[🔍 Filter] [⟲ Reset]
```

### **Cara Menggunakan:**

**Scenario 1: Filter Kelas Saja**

```
1. Pilih Kelas: "Kelas 10 - IPA A"
2. Matapelajaran: (kosong)
3. Klik [🔍 Filter]
→ Tampil semua file dari Kelas 10 IPA A
```

**Scenario 2: Filter Matapelajaran Saja**

```
1. Kelas: "-- Semua Kelas --"
2. Matapelajaran: ketik "Matematika"
3. Klik [🔍 Filter]
→ Tampil file dari semua Anak Bangau dengan matapelajaran "Matematika"
```

**Scenario 3: Filter Kelas + Matapelajaran**

```
1. Pilih Kelas: "Kelas 11 - IPS D"
2. Matapelajaran: ketik "PKN"
3. Klik [🔍 Filter]
→ Tampil file dari Kelas 11 IPS D dengan matapelajaran "PKN"
```

### **Tombol Reset:**

```
Klik [⟲ Reset] untuk:
- Kosongkan semua filter
- Kembali ke tampilan awal (semua file)
- Clear search text
```

---

## 📊 FITUR 4: Tabel Data

### **Kolom-Kolom:**

```
┌──────────┬────────┬──────────┬────────────┬──────────┬──────────┬──────────┬─────────┐
│ Siswa    │ Kelas  │ Judul    │ Matapel    │ Tipe     │ Tanggal  │ Kehadiran│ Aksi    │
├──────────┼────────┼──────────┼────────────┼──────────┼──────────┼──────────┼─────────┤
│ Ahmad    │ 10 A   │ Tugas..  │ Matematika │ PDF      │ 5 Nov    │ ✓ 85%    │ Download│
│ Kusuma   │        │          │            │          │ 10:30    │ 17/20    │         │
├──────────┼────────┼──────────┼────────────┼──────────┼──────────┼──────────┼─────────┤
│ Budi     │ 10 B   │ Essay..  │ B.Inggris  │ DOCX     │ 4 Nov    │ ⚠ 75%    │ Download│
│ Rahman   │        │          │            │          │ 14:20    │ 15/20    │         │
└──────────┴────────┴──────────┴────────────┴──────────┴──────────┴──────────┴─────────┘
```

### **Penjelasan Kolom:**

#### **1. Siswa**

```
Menampilkan:
- Nama lengkap siswa
- ID siswa (untuk referensi)

Contoh:
Ahmad Kusuma
ID: 5
```

#### **2. Kelas**

```
Menampilkan:
- Nama kelas dalam badge biru
- Format: "Kelas 10 - IPA A"

Contoh:
┌─────────────────┐
│ Kelas 10 - IPA A│
└─────────────────┘
```

#### **3. Judul**

```
Menampilkan:
- Judul file yang diupload siswa
- Subtitle/Deskripsi (jika ada)

Contoh:
Tugas Matematika
Bab 5 - Persamaan Kuadrat
```

#### **4. Matapelajaran**

```
Menampilkan:
- Nama mata pelajaran

Contoh:
- Matematika
- Bahasa Inggris
- Fisika
- Kimia
- PKN
```

#### **5. Tipe File**

```
Menampilkan:
- Format file dalam badge ungu
- Besar huruf

Contoh:
┌────────┐
│  PDF   │
└────────┘

Tipe file yang didukung:
- PDF (Dokumen)
- DOCX / DOC (Microsoft Word)
- XLSX / XLS (Excel)
- PPTX / PPT (PowerPoint)
- JPG / JPEG / PNG / GIF (Gambar)
- ZIP / RAR / 7Z (Kompresi)
- TXT (Text)
```

#### **6. Tanggal Upload**

```
Menampilkan:
- Tanggal dan jam upload
- Format: DD Mon YYYY HH:MM

Contoh:
5 Nov 2025 10:30
4 Nov 2025 14:20
3 Nov 2025 09:15
```

#### **7. 📊 Kehadiran (PENTING!)**

```
Menampilkan:
- Presentase kehadiran siswa
- Badge warna:
  🟢 Hijau (≥80%)  = Kehadiran Baik
  🟡 Kuning (70-79%) = Kehadiran Cukup
  🔴 Merah (<70%)  = Kehadiran Kurang
- Detail: X/Y pertemuan

Contoh:
┌──────────────┐
│ ✓ 85%        │
│ 17/20        │
└──────────────┘

Artinya:
- Siswa hadir 17 dari 20 pertemuan jadwal
- Presentase kehadiran: 85%
- Status: Baik ✓
```

#### **8. Aksi**

```
Menampilkan:
- Tombol Download [⬇️ Download]

Klik untuk:
- Download file dari siswa
- File akan disimpan ke folder Downloads
```

---

## 📥 FITUR 5: Download File

### **Cara Download:**

```
1. Cari file yang ingin didownload
2. Klik tombol [⬇️ Download] di kolom Aksi
3. File akan download ke folder Downloads Anda
4. File siap dibuka/dipergunakan
```

### **Contoh Penggunaan:**

```
Saya ingin download "Tugas Matematika" dari Ahmad
↓
1. Cari file "Tugas Matematika" di tabel
2. Lihat baris: Ahmad | Kelas 10 IPA A | Tugas Matematika | ...
3. Klik [⬇️ Download]
4. File PDF "Tugas Matematika" tersimpan di Downloads
5. Buka dan review tugas siswa
```

---

## 📖 FITUR 6: Pagination

### **Tampilan:**

```
Jika file lebih dari 20:
┌─────────────────────────────────────────┐
│ << < 1 2 3 4 5 > >>                     │
└─────────────────────────────────────────┘
```

### **Cara Menggunakan:**

```
- Halaman 1: File 1-20
- Halaman 2: File 21-40
- Halaman 3: File 41-60
- dst...

Klik nomor halaman untuk pindah
Klik < untuk halaman sebelumnya
Klik > untuk halaman berikutnya
Klik << untuk halaman pertama
Klik >> untuk halaman terakhir
```

---

## 🎓 MEMBACA PRESENTASE KEHADIRAN

### **Arti Warna:**

#### **🟢 HIJAU (≥80%) - Kehadiran Baik**

```
✓ 85%
17/20 pertemuan

Status: Siswa ini rajin hadir
Action: Pertahankan prestasi, jadi role model
```

#### **🟡 KUNING (70-79%) - Kehadiran Cukup**

```
⚠ 75%
15/20 pertemuan

Status: Kehadiran cukup, perlu dimonitor
Action: Follow-up, cek ada alasan apa?
       Ingatkan pentingnya kehadiran
```

#### **🔴 MERAH (<70%) - Kehadiran Kurang**

```
✗ 60%
12/20 pertemuan

Status: Kehadiran KURANG, sangat perlu perhatian
Action: Hubungi wali siswa
       Cari tahu penyebab absensi
       Buat action plan recovery
```

---

## 💡 USE CASES

### **Kasus 1: Evaluasi Prestasi Siswa**

```
Guru ingin lihat dokumen dari kelas 10 IPA A
↓
1. Buka halaman Dokumen Siswa
2. Filter Kelas → Pilih "Kelas 10 - IPA A"
3. Lihat file yang diupload siswa
4. Perhatikan kolom "Kehadiran"
5. Evaluasi: Siswa dengan kehadiran tinggi biasanya punya dokumen berkualitas
```

### **Kasus 2: Identifikasi Siswa Bermasalah**

```
Guru ingin tahu siswa mana yang sering absen
↓
1. Buka halaman Dokumen Siswa
2. Lihat kolom "Kehadiran"
3. Cari badge merah (< 70%)
4. Siswa-siswa dengan badge merah perlu perhatian khusus
5. Follow-up ke siswa atau wali siswa
```

### **Kasus 3: Validasi Data Absensi**

```
Guru ingin cross-check antara file siswa dan kehadirannya
↓
1. Buka halaman Dokumen Siswa
2. Lihat file + kehadiran bersamaan
3. Jika kehadiran rendah tapi banyak file → kemungkinan data absensi error
4. Cek sistem absensi
5. Validasi ulang jika perlu
```

### **Kasus 4: Audit Kelas Tertentu**

```
Koordinator ingin audit kelas 11 IPS D
↓
1. Filter Kelas → "Kelas 11 - IPS D"
2. Lihat semua siswa + dokumen + kehadiran
3. Identifikasi siswa yang underperform
4. Lihat file quality vs kehadiran
5. Buat laporan audit
```

---

## ⚙️ TIPS & TRIK

### **Tip 1: Filter Kombinasi**

```
Kombinasikan filter untuk hasil lebih spesifik
Contoh:
- Kelas 10 IPA A + Matematika
- = File Matematika dari Kelas 10 IPA A saja
```

### **Tip 2: Cek Kehadiran Terlebih Dahulu**

```
Sebelum mengevaluasi file siswa, perhatikan kehadiran
- Kehadiran tinggi + file bagus = Siswa rajin
- Kehadiran rendah + file sedikit = Perlu bantuan
- Kehadiran tinggi + file sedikit = Mungkin sibuk?
```

### **Tip 3: Export untuk Laporan**

```
Bisa screenshot halaman untuk:
- Laporan ke kepala sekolah
- Dokumentasi evaluasi siswa
- Referensi pertemuan wali murid
```

### **Tip 4: Gunakan Tombol Reset**

```
Jika filter membingungkan, klik [⟲ Reset]
Kembali ke tampilan awal
Mulai filter lagi dengan lebih hati-hati
```

---

## ❓ FAQ (Pertanyaan Umum)

### **Q1: Mengapa tidak ada siswa yang ditampilkan?**

```
Kemungkinan:
1. Siswa belum upload dokumen
   → Hubungi siswa untuk upload
2. Filter terlalu ketat
   → Klik [⟲ Reset] untuk lihat semua
3. Tidak ada jadwal (lessons) di kelas
   → Hubungi admin, mungkin ada kesalahan setup
```

### **Q2: Presentase kehadiran selalu 0%?**

```
Kemungkinan:
1. Sistem absensi belum diinput
   → Lakukan absensi di fitur Absensi
2. Jadwal belum dibuat
   → Hubungi admin untuk setup jadwal
3. Siswa belum ada attendance records
   → Check apakah siswa terdaftar di kelas
```

### **Q3: File tidak bisa didownload?**

```
Kemungkinan:
1. File error/rusak
   → Minta siswa upload ulang
2. Storage penuh
   → Hubungi admin
3. File sudah dihapus
   → Check sistem penyimpanan
```

### **Q4: Bagaimana jika ada siswa pindah kelas?**

```
Sistem akan otomatis:
- Tampilkan file dari class_room terbaru siswa
- Jika ada attendance, hitung dari kelas baru
- File lama tetap tersimpan di database
```

### **Q5: Bisa filter berdasarkan guru pengajar?**

```
Fitur ini menampilkan:
- Semua file dari siswa
- Dari semua guru yang mengajar
- Hubungi admin jika perlu filter per guru
```

---

## 📱 KEYBOARD SHORTCUTS

```
Ctrl + F → Browser search (cari di halaman)
Ctrl + P → Print halaman (untuk laporan)
Ctrl + C → Copy data dari tabel
F5 → Refresh halaman
```

---

## 🔐 KEAMANAN & PRIVASI

### **Data yang Dilindungi:**

```
✓ File siswa hanya bisa diakses guru
✓ Download akan tercatat di server
✓ Data kehadiran real-time dari sistem absensi
✓ Tidak ada perubahan data di halaman ini
  (guru hanya bisa view & download)
```

---

## 📞 BANTUAN TEKNIS

### **Jika Menemukan Masalah:**

1. Screenshot halaman
2. Catat waktu terjadi error
3. Catat langkah-langkah sebelum error
4. Hubungi IT/Admin dengan informasi tersebut

### **Contact IT:**

-   WhatsApp: [IT Support Number]
-   Email: it@alwicollege.sch.id
-   Zoom Meeting: [Support Link]

---

## 🎯 CHECKLIST Penggunaan Pertama

-   [ ] Login dengan akun guru
-   [ ] Buka halaman Dashboard
-   [ ] Klik card "Dokumen"
-   [ ] Lihat halaman "Dokumen Siswa"
-   [ ] Coba filter Kelas
-   [ ] Coba filter Matapelajaran
-   [ ] Lihat tabel dengan file + kehadiran
-   [ ] Coba klik Download file
-   [ ] Klik Reset untuk kembali
-   [ ] Pahami arti badge kehadiran (hijau/kuning/merah)
-   [ ] Baca FAQ jika ada pertanyaan

---

**Version:** 3.1 - Panduan Pengguna  
**Date:** November 5, 2025  
**Audience:** Guru Anak Bangau  
**Status:** 📚 READY TO USE
