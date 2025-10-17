# 👨‍💻 Info Page - Developer Cheat Sheet

Panduan cepat untuk developer yang perlu memodifikasi kode Info Page.

---

## 🎯 Quick Navigation

| Komponen       | File                                                                | Baris     | Fungsi               |
| -------------- | ------------------------------------------------------------------- | --------- | -------------------- |
| **Form View**  | `resources/views/info/index.blade.php`                              | 1-300     | Student form & list  |
| **Admin View** | `resources/views/info/list.blade.php`                               | 1-200     | Admin all files view |
| **Controller** | `app/Http/Controllers/InfoFileController.php`                       | store()   | Handle upload        |
| **Model**      | `app/Models/InfoFile.php`                                           | $fillable | 6 fields config      |
| **Migration**  | `database/migrations/2025_10_14_163046_create_info_files_table.php` | schema()  | DB structure         |

---

## 📝 Code Snippets

### 1️⃣ Add New Field (Contoh: Guru Pembimbing)

**Step 1: Update Migration**

```php
// database/migrations/2025_10_14_163046_create_info_files_table.php
Schema::create('info_files', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained('students');
    $table->string('school')->nullable();
    $table->string('class_name')->nullable();
    $table->string('subject')->nullable();
    $table->string('title');
    $table->string('material')->nullable();
    $table->string('teacher_name')->nullable();  // ✅ NEW FIELD
    $table->string('file_path');
    $table->timestamps();
});
```

**Step 2: Update Model**

```php
// app/Models/InfoFile.php
protected $fillable = [
    'student_id',
    'title',
    'file_path',
    'school',
    'class_name',
    'subject',
    'material',
    'teacher_name',  // ✅ NEW FIELD
];
```

**Step 3: Update Controller**

```php
// app/Http/Controllers/InfoFileController.php
public function store(Request $r) {
    $r->validate([
        'school' => 'nullable|max:120',
        'class_name' => 'nullable|max:50',
        'subject' => 'nullable|max:120',
        'title' => 'required|max:120',
        'material' => 'nullable|max:255',
        'teacher_name' => 'nullable|max:120',  // ✅ NEW FIELD
        'file' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
    ]);

    $student = Student::firstOrCreate(['user_id' => Auth::id()]);
    $path = $r->file('file')->store('info_files', 'public');

    InfoFile::create([
        'student_id' => $student->id,
        'school' => $r->school,
        'class_name' => $r->class_name,
        'subject' => $r->subject,
        'title' => $r->title,
        'material' => $r->material,
        'teacher_name' => $r->teacher_name,  // ✅ NEW FIELD
        'file_path' => $path,
    ]);
}
```

**Step 4: Update Form View**

```blade
<!-- resources/views/info/index.blade.php -->
<label class="block text-sm font-semibold text-gray-700 mb-2">Guru Pembimbing</label>  <!-- ✅ NEW -->
<input
    type="text"
    name="teacher_name"  <!-- ✅ NEW -->
    placeholder="Nama guru pembimbing"
    value="{{ old('teacher_name') }}"
    class="w-full px-4 py-3 border-2 border-blue-300 rounded-full focus:outline-none focus:border-blue-600 transition"
/>
```

**Step 5: Update Admin View**

```blade
<!-- resources/views/info/list.blade.php -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
    <div>
        <p class="text-gray-600">Sekolah</p>
        <p class="font-semibold">{{ $file->school }}</p>
    </div>
    <div>
        <p class="text-gray-600">Kelas</p>
        <p class="font-semibold">{{ $file->class_name }}</p>
    </div>
    <div>
        <p class="text-gray-600">Pelajaran</p>
        <p class="font-semibold">{{ $file->subject }}</p>
    </div>
    <div>
        <p class="text-gray-600">Guru Pembimbing</p>  <!-- ✅ NEW -->
        <p class="font-semibold">{{ $file->teacher_name }}</p>  <!-- ✅ NEW -->
    </div>
</div>
```

**Step 6: Run Migration**

```bash
php artisan migrate
# Or if adding to existing table:
php artisan make:migration add_teacher_name_to_info_files_table
```

---

### 2️⃣ Change File Upload Size Limit (contoh: dari 10MB ke 50MB)

**Location**: `app/Http/Controllers/InfoFileController.php`

```php
// BEFORE:
'file' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',  // 10MB

// AFTER:
'file' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:51200',  // 50MB
```

**Also update**: `config/filesystems.php`

```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
        'max_size' => 52428800,  // 50MB in bytes
    ],
],
```

---

### 3️⃣ Add File Type Restriction (Contoh: Hanya PDF)

**In Controller**:

```php
'file' => 'required|mimes:pdf|max:10240',  // Hanya PDF, 10MB
```

**Or Multiple Specific Types**:

```php
'file' => 'required|mimes:pdf,jpg,jpeg|max:10240',  // PDF & Images, 10MB
```

---

### 4️⃣ Add Validation Message Customization

```php
$r->validate([
    'school' => 'nullable|max:120',
    'class_name' => 'nullable|max:50',
    'subject' => 'nullable|max:120',
    'title' => 'required|max:120',
    'material' => 'nullable|max:255',
    'file' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
], [
    'school.max' => 'Nama sekolah maksimal 120 karakter',
    'class_name.max' => 'Nama kelas maksimal 50 karakter',
    'title.required' => 'Judul file wajib diisi',
    'file.required' => 'File wajib dipilih',
    'file.mimes' => 'Format file harus: PDF, DOC, DOCX, JPG, JPEG, PNG',
    'file.max' => 'Ukuran file maksimal 10MB',
]);
```

---

### 5️⃣ Modify Auto-Fill JavaScript

**Current Logic**: Auto-fill title & materi dari filename

**File**: `resources/views/info/index.blade.php` (dalam JavaScript)

```javascript
// CURRENT CODE:
function updateFileName(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const nameWithoutExt = file.name.replace(/\.[^/.]+$/, "");
        document.getElementById("title").value = nameWithoutExt;
        if (!document.getElementById("material").value) {
            document.getElementById("material").value = nameWithoutExt;
        }
        document.getElementById("fileName").textContent = file.name;
        document.getElementById("fileNameDisplay").classList.remove("hidden");
    }
}

// MODIFIED: Hanya auto-fill title, tidak materi
function updateFileName(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const nameWithoutExt = file.name.replace(/\.[^/.]+$/, "");
        document.getElementById("title").value = nameWithoutExt;
        // Hapus baris yang auto-fill material
        document.getElementById("fileName").textContent = file.name;
        document.getElementById("fileNameDisplay").classList.remove("hidden");
    }
}

// MODIFIED: Auto-fill title dengan uppercase
function updateFileName(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const nameWithoutExt = file.name.replace(/\.[^/.]+$/, "");
        document.getElementById("title").value = nameWithoutExt.toUpperCase(); // ✅ UPPERCASE
        if (!document.getElementById("material").value) {
            document.getElementById("material").value = nameWithoutExt;
        }
        document.getElementById("fileName").textContent = file.name;
        document.getElementById("fileNameDisplay").classList.remove("hidden");
    }
}
```

---

### 6️⃣ Add Storage Path for Downloads

**Default**: `storage/app/public/info_files/`

**To Change**:

```php
// In InfoFileController store() method:

// CHANGE FROM:
$path = $r->file('file')->store('info_files', 'public');

// CHANGE TO:
$path = $r->file('file')->store('kisi_kisi_files', 'public');  // Different folder
// or
$path = $r->file('file')->store('uploads/info', 'public');     // Nested folder
```

---

### 7️⃣ Add File Preview Capability

**Add to index.blade.php after download button**:

```blade
<!-- PDF Preview -->
@if(Str::endsWith($file->file_path, '.pdf'))
    <a href="{{ Storage::url($file->file_path) }}"
       target="_blank"
       class="text-blue-600 hover:text-blue-800 text-sm">
        👁️ Preview
    </a>
@endif

<!-- Image Preview -->
@if(in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
    <img src="{{ Storage::url($file->file_path) }}"
         class="max-w-xs mt-2 rounded"
         alt="Preview">
@endif
```

---

## 🛠️ Common Issues & Solutions

### ❌ File tidak ter-upload

**Solusi**:

1. Check storage symlink: `php artisan storage:link`
2. Check folder permissions: `chmod 755 storage/app/public`
3. Check disk config: `config/filesystems.php`

### ❌ Auto-fill tidak bekerja

**Solusi**:

1. Open browser console (F12 → Console)
2. Check error messages
3. Verify element IDs match: `title`, `material`, `file`
4. Check JavaScript tidak ada error

### ❌ Download file error

**Solusi**:

1. Verify file exists: `storage/app/public/info_files/`
2. Check permissions: `chmod 644 storage/app/public/info_files/*`
3. Verify path in database correct

### ❌ Delete tidak bekerja

**Solusi**:

1. Check user authorization
2. Verify CSRF token in form
3. Check route permissions

---

## 📊 Database Queries

### Lihat semua file user

```sql
SELECT * FROM info_files
WHERE student_id = (SELECT id FROM students WHERE user_id = 1)
ORDER BY created_at DESC;
```

### Lihat semua file total

```sql
SELECT COUNT(*) FROM info_files;
```

### Lihat file per kelas

```sql
SELECT class_name, COUNT(*) as total
FROM info_files
GROUP BY class_name
ORDER BY total DESC;
```

### Lihat file per pelajaran

```sql
SELECT subject, COUNT(*) as total
FROM info_files
GROUP BY subject
ORDER BY total DESC;
```

### Delete file lebih dari 6 bulan

```sql
DELETE FROM info_files
WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

---

## 🎨 Tailwind CSS Classes Reference

```
Sizing:
  w-full        → width 100%
  max-w-xs      → max-width extra small
  px-4 py-3     → padding horizontal 1rem, vertical 0.75rem

Colors:
  text-gray-700     → Dark gray text
  bg-blue-600       → Blue background
  border-blue-300   → Light blue border
  hover:bg-blue-700 → Dark blue on hover

Layout:
  flex flex-col     → Column layout
  grid grid-cols-1  → 1 column on mobile
  md:grid-cols-2    → 2 columns on tablet+
  gap-3             → Space between items

Borders & Shadows:
  border-2          → 2px border
  rounded-full      → Fully rounded
  rounded-lg        → Large rounded corners
  shadow-md         → Medium shadow
  hover:shadow-md   → Shadow on hover

Display:
  hidden            → display: none
  block             → display: block
  inline-block      → display: inline-block
```

---

## 🚀 Performance Tips

### 1. Add Pagination untuk Admin View

```blade
@forelse($files->paginate(10) as $file)
    <!-- Display file -->
@empty
    <p>Tidak ada file</p>
@endforelse
```

### 2. Add Search/Filter

```php
// In InfoFileController
$files = InfoFile::where('subject', $request->subject)->get();
```

### 3. Add File Compression

```php
// Before store
$file = $r->file('file');
if($file->getClientMimeType() == 'image/jpeg') {
    // Compress image
}
```

### 4. Cache List Queries

```php
$files = Cache::remember('info_files', 3600, function() {
    return InfoFile::with('student')->get();
});
```

---

## 🔐 Security Checklist

-   ✅ Validate file type in backend
-   ✅ Validate file size limit
-   ✅ Check user authorization (own files only)
-   ✅ Sanitize filename
-   ✅ Use CSRF token on form
-   ✅ Store files outside public folder (optional)
-   ✅ Rate limit file uploads (optional)
-   ✅ Log file operations (optional)

---

## 📱 Responsive Classes

```
Mobile (< 576px):    No prefix needed
Tablet (576px+):     sm:
Desktop (768px+):    md:
Large (992px+):      lg:
XL (1200px+):        xl:
```

Example:

```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
    <!-- Mobile: 1 col, Tablet+: 2 cols, Large+: 4 cols -->
</div>
```

---

## 🧪 Testing Commands

```bash
# Test upload endpoint
curl -F "file=@test.pdf" http://localhost:8000/info

# Run specific test
php artisan test tests/Feature/InfoFileTest.php

# Check storage link
php artisan storage:link

# Clear cache
php artisan cache:clear

# Fresh migration
php artisan migrate:fresh --seed
```

---

## 📖 Related Documentation

-   INFO_PAGE_DOCUMENTATION.md - Full technical docs
-   INFO_PAGE_QUICK_SUMMARY.md - Quick reference
-   INFO_PAGE_VISUAL_GUIDE.md - Visual layout guide
-   Laravel Migration Docs: https://laravel.com/docs/migrations
-   Laravel Validation: https://laravel.com/docs/validation
-   Tailwind CSS: https://tailwindcss.com/

---

## 🎓 Learning Resources

-   How to modify Laravel forms
-   Understanding Blade templating
-   Working with file uploads in Laravel
-   Tailwind CSS styling guide
-   JavaScript form manipulation
-   Database migrations best practices

---

**Last Updated**: October 17, 2025
**Version**: 1.0.0
**Status**: ✅ Ready to Use

---

_Happy Coding! 👨‍💻_
