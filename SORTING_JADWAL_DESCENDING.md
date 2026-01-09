# 📊 Perubahan Sorting - Jadwal Pelajaran (Descending)

## Ringkasan

Pengurutan nama guru dan materi pada bagian pembuatan jadwal telah diubah dari ascending menjadi **descending order**.

## Perubahan yang Dilakukan

### File: `app/Http/Controllers/LessonController.php`

#### 1. Method `showGenerate()` (Line 21-30)

**Sebelum:**

```php
$teachersList = Teacher::with('user')->orderBy('id')->get();
$subjectsList = Subject::orderBy('name')->get();
```

**Sesudah:**

```php
$teachersList = Teacher::with('user')->orderBy('id', 'desc')->get();
$subjectsList = Subject::orderBy('name', 'desc')->get();
```

**Lokasi**: Halaman Generate Jadwal (Create Schedule)

-   Guru ditampilkan dari ID terbesar ke terkecil
-   Materi ditampilkan dari nama Z sampai A

#### 2. Method `adminView()` (Line 301)

**Sebelum:**

```php
$teachers = Teacher::with('user')->orderBy('id')->get();
```

**Sesudah:**

```php
$teachers = Teacher::with('user')->orderBy('id', 'desc')->get();
```

**Lokasi**: Halaman Manajemen Jadwal/Edit

-   Guru ditampilkan dari ID terbesar ke terkecil

#### 3. Method `editLesson()` (Line 311-316)

**Sebelum:**

```php
$subjectsList = Subject::orderBy('name')->get();
```

**Sesudah:**

```php
$subjectsList = Subject::orderBy('name', 'desc')->get();
```

**Lokasi**: Halaman Edit Jadwal Individual

-   Materi ditampilkan dari nama Z sampai A

## Dampak Visual

### Sebelum:

-   Guru: Guru 1, Guru 2, Guru 3, ... (ascending by ID)
-   Materi: Aljabar, Biologi, Fisika, ... (ascending alphabetical)

### Sesudah:

-   Guru: Guru 3, Guru 2, Guru 1, ... (descending by ID)
-   Materi: Fisika, Biologi, Aljabar, ... (descending alphabetical)

## Halaman yang Terpengaruh

1. ✅ **Generate Jadwal** (`/admin/jadwal/generate`)

    - Dropdown Guru: Descending
    - Dropdown Materi: Descending

2. ✅ **Manajemen Jadwal** (`/admin/jadwal/list`)

    - Filter Guru: Descending

3. ✅ **Edit Jadwal** (`/admin/jadwal/{id}/edit`)
    - Dropdown Materi: Descending

## Testing Checklist

-   [x] Guru tampil descending di form generate
-   [x] Materi tampil descending di form generate
-   [x] Guru tampil descending di filter edit
-   [x] Materi tampil descending di form edit
-   [x] Tidak ada SQL error
-   [x] Database query tetap optimal

## File yang Dimodifikasi

-   `app/Http/Controllers/LessonController.php`

---

**Status**: ✅ Selesai  
**Tanggal**: 9 January 2026
