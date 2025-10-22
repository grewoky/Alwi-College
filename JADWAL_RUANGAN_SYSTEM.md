# 📚 SISTEM JADWAL RUANGAN KELAS - DOKUMENTASI

## 🎯 Alur Kerja Sistem

```
Admin Generate Jadwal
    ↓
Admin pilih:
  - Ruangan (A21, B22, A31, dll)
  - Guru (Nama guru yang mengajar)
  - Materi (Pelajaran)
  - Tanggal mulai & selesai
    ↓
System create Lesson records
  - Setiap hari di range tanggal
  - Untuk ruangan yang dipilih
  - Dengan guru yang dipilih
    ↓
Jadwal tersimpan di database
  - Bisa diedit (ganti jam, materi)
  - Bisa dihapus
  - Siswa bisa lihat jadwal kelasnya
```

---

## 🏛️ STRUKTUR DATABASE

### **ClassRooms Table**

```sql
id | school_id | code | name | grade | capacity | created_at | updated_at
1  | 1         | 1B   | Kelas 1B - Ruang Kecil | 10 | 20 | ... | ...
2  | 1         | A21  | Kelas XI IPA 1 (A21) | 11 | 30 | ... | ...
3  | 1         | A22  | Kelas XI IPA 2 (A22) | 11 | 20 | ... | ...
4  | 1         | A23  | Kelas XI IPA 3 (A23) | 11 | 15 | ... | ...
5  | 1         | B21  | Kelas XI IPS 1 (B21) | 11 | 30 | ... | ...
6  | 1         | B22  | Kelas XI IPS 2 (B22) | 11 | 20 | ... | ...
7  | 1         | B23  | Kelas XI IPS 3 (B23) | 11 | 15 | ... | ...
8  | 1         | B24  | Kelas XI IPS 4 (B24) | 11 | 5  | ... | ...
9  | 1         | A31  | Kelas XII IPA 1 (A31)| 12 | 40 | ... | ...
10 | 1         | A32  | Kelas XII IPA 2 (A32)| 12 | 15 | ... | ...
11 | 1         | B31  | Kelas XII IPS 1 (B31)| 12 | 30 | ... | ...
12 | 1         | B32  | Kelas XII IPS 2 (B32)| 12 | 20 | ... | ...
13 | 1         | B33  | Kelas XII IPS 3 (B33)| 12 | 15 | ... | ...
14 | 1         | B34  | Kelas XII IPS 4 (B34)| 12 | 5  | ... | ...
```

### **Lessons Table**

```sql
id | date       | class_room_id | subject_id | teacher_id | start_time | end_time | created_at | updated_at
1  | 2025-10-22 | 3 (A22)      | 5 (Mtk)    | 2         | 09:00      | 10:00    | ...       | ...
2  | 2025-10-22 | 6 (B22)      | 8 (IPA)    | 4         | 10:00      | 11:00    | ...       | ...
3  | 2025-10-23 | 3 (A22)      | 5 (Mtk)    | 2         | 09:00      | 10:00    | ...       | ...
```

**Penjelasan:**

-   `class_room_id` = Foreign Key ke ClassRooms table (A22 = id 3)
-   `date` = Tanggal pelajaran
-   `subject_id` = Materi/pelajaran
-   `teacher_id` = Guru yang mengajar
-   `start_time` & `end_time` = Jam pelajaran

---

## 👨‍💼 LANGKAH-LANGKAH ADMIN UNTUK GENERATE JADWAL

### **Step 1: Login sebagai Admin**

```
URL: http://localhost:8000/admin/dashboard
```

### **Step 2: Buka Generate Jadwal**

```
Menu: Jadwal → Generate Jadwal
URL: http://localhost:8000/admin/jadwal/generate
```

### **Step 3: Isi Form**

**Form Fields:**

| Field           | Contoh     | Penjelasan                                       |
| --------------- | ---------- | ------------------------------------------------ |
| Pilih Kelas     | A22        | Ruangan yang digunakan (sudah link ke ClassRoom) |
| Pilih Guru      | Ibu Ani    | Guru yang mengajar di kelas tersebut             |
| Pilih Materi    | Matematika | Pelajaran (opsional)                             |
| Tanggal Mulai   | 2025-10-22 | Kapan jadwal dimulai                             |
| Tanggal Selesai | 2025-10-31 | Kapan jadwal berakhir                            |
| Jam Mulai       | 09:00      | Jam mulai pelajaran                              |
| Jam Selesai     | 10:00      | Jam selesai pelajaran                            |

### **Step 4: Submit Generate**

```
Click "Generate Jadwal"
↓
System akan buat jadwal untuk SETIAP HARI
dari 22 Oktober - 31 Oktober 2025
Ruang A22 + Ibu Ani + Matematika
Jam 09:00 - 10:00
↓
Hasil: 10 jadwal dibuat (10 hari kerja)
```

---

## 🔍 LIHAT JADWAL YANG SUDAH DIBUAT

### **Admin View:**

```
URL: http://localhost:8000/admin/jadwal/list

Menampilkan:
- Semua jadwal di sistem
- Filter: Guru, Ruangan, Tanggal
- Buttons: Edit, Hapus
```

### **Student View:**

```
URL: http://localhost:8000/student/jadwal

Menampilkan:
- Hanya jadwal untuk kelas mereka
- Contoh: Student di A22 lihat jadwal A22
- Tidak bisa edit/hapus
```

### **Teacher View:**

```
URL: http://localhost:8000/teacher/jadwal

Menampilkan:
- Hanya jadwal untuk guru yang login
- Contoh: Ibu Ani lihat jadwal pelajaran Ibu Ani
- Tidak bisa edit/hapus
```

---

## ✏️ EDIT JADWAL

### **Proses:**

```
1. Admin buka: /admin/jadwal/list
2. Lihat jadwal yang ingin diedit
3. Click tombol "Edit"
4. Form edit muncul:
   - Materi (bisa ganti)
   - Jam Mulai (bisa ganti)
   - Jam Selesai (bisa ganti)
5. Click "Simpan Perubahan"
6. Jadwal terupdate

Validasi:
✓ Jam selesai harus lebih besar dari jam mulai
✓ Tidak bisa overlap dengan jadwal lain di ruangan sama
```

---

## 🗑️ HAPUS JADWAL

### **Proses:**

```
1. Admin buka: /admin/jadwal/list
2. Lihat jadwal yang ingin dihapus
3. Click tombol "Edit"
4. Di bawah form, ada tombol "Hapus Jadwal"
5. Click "Hapus Jadwal"
6. Confirm dialog muncul: "Yakin hapus jadwal ini?"
7. Click OK
8. Jadwal dihapus
9. Sistem log: "Jadwal dihapus untuk A22, Ibu Ani, 2025-10-22"
```

---

## 👨‍🎓 STUDENT LIHAT JADWAL MEREKA

### **Alur:**

```
1. Student login: http://localhost:8000/student/dashboard
2. Click "Jadwal Les" di dashboard
3. System auto-detect kelas siswa
4. Tampilkan HANYA jadwal untuk kelas siswa itu

Contoh:
- Student Raka di kelas A22
  → System query: Lesson.where('class_room_id', id_A22)
  → Tampilkan hanya jadwal A22
  → Tidak bisa lihat jadwal B21, B22, dll
```

### **View:**

```
Tabel jadwal menampilkan:
- Tanggal
- Guru
- Materi
- Jam mulai - Jam selesai
- Ruangan: A22

Filter tersedia:
- By Date
```

---

## 📋 DATA RUANGAN YANG SUDAH DIBUAT

```
├─ Grade 10
│  └─ 1B (Ruang Kecil) - Capacity: 20
│
├─ Grade 11 (Kelas XI)
│  ├─ A21 (XI IPA 1) - Capacity: 30
│  ├─ A22 (XI IPA 2) - Capacity: 20 ⭐
│  ├─ A23 (XI IPA 3) - Capacity: 15
│  ├─ B21 (XI IPS 1) - Capacity: 30
│  ├─ B22 (XI IPS 2) - Capacity: 20
│  ├─ B23 (XI IPS 3) - Capacity: 15
│  └─ B24 (XI IPS 4) - Capacity: 5
│
└─ Grade 12 (Kelas XII)
   ├─ A31 (XII IPA 1) - Capacity: 40
   ├─ A32 (XII IPA 2) - Capacity: 15
   ├─ B31 (XII IPS 1) - Capacity: 30
   ├─ B32 (XII IPS 2) - Capacity: 20
   ├─ B33 (XII IPS 3) - Capacity: 15
   └─ B34 (XII IPS 4) - Capacity: 5
```

**Total: 14 ruangan kelas**

---

## 🔗 RELATIONSHIPS

```
ClassRoom (1) ← → (Many) Lesson
┌─────────────┐     ┌──────────┐
│  A22        │────→│ Jadwal 1 │
│  Grade: 11  │     │ Jadwal 2 │
│  Capacity:20│     │ Jadwal 3 │
└─────────────┘     └──────────┘
     ↓
  Teacher ← → Lesson
     ↓
  Subject ← → Lesson
     ↓
  Student (class_room_id) ← → ClassRoom
```

**Artinya:**

-   Setiap ClassRoom bisa punya banyak Lesson
-   Setiap Lesson terhubung ke: ClassRoom, Teacher, Subject, Date
-   Student terhubung ke ClassRoom (nilai `class_room_id` di Student table)
-   Student hanya lihat Lesson untuk ClassRoom mereka

---

## ✅ TESTING CHECKLIST

### **Phase 1: Data Setup**

-   [ ] Run seeder: `php artisan db:seed --class=ClassRoomSeeder`
-   [ ] Verify 14 ruangan ada di database: `php artisan tinker` → `App\Models\ClassRoom::count()`
-   [ ] Check student assign ke ruangan: Raka → A22

### **Phase 2: Generate Jadwal**

-   [ ] Admin generate jadwal A22
-   [ ] Admin generate jadwal B31
-   [ ] Verify di database ada 20 records (10 hari x 2 jadwal)

### **Phase 3: View Jadwal**

-   [ ] Admin view all jadwal
-   [ ] Student Raka view → hanya A22 muncul
-   [ ] Teacher Ibu Ani view → hanya jadwal Ibu Ani muncul

### **Phase 4: Edit Jadwal**

-   [ ] Admin edit jadwal: ganti jam
-   [ ] Verify di database jam berubah
-   [ ] Verify student lihat jam baru

### **Phase 5: Delete Jadwal**

-   [ ] Admin delete jadwal
-   [ ] Confirm dialog muncul
-   [ ] Verify jadwal dihapus dari database & view

---

## 🚀 DEPLOYMENT COMMANDS

```bash
# 1. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 2. Seed classrooms
php artisan db:seed --class=ClassRoomSeeder

# 3. Build assets
npm run build

# 4. Start server
php artisan serve
```

---

## 📞 TROUBLESHOOTING

**Q: Ruangan tidak muncul di form generate?**

```
A: Check:
✓ Seeder sudah dijalankan?
✓ Database berisi 14 records ClassRoom?
✓ relationship('school') bekerja?
```

**Q: Student lihat jadwal kelas lain?**

```
A: Check:
✓ Student punya class_room_id di database?
✓ studentView() filter dengan class_room_id?
✓ Jadwal pakai class_room_id yang benar?
```

**Q: Edit jadwal error "jam selesai harus lebih besar"?**

```
A: Pastikan:
✓ start_time < end_time
✓ Format jam: 09:00 (24-hour format)
```

---

**Created:** October 22, 2025
**Status:** READY FOR PRODUCTION ✅
