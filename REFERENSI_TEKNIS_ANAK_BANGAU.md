# 🔧 REFERENSI TEKNIS - ANAK BANGAU DOKUMEN SISWA

---

## 📂 Struktur Database (Relasi)

```
ClassRoom (grade 10, 11, 12)
    ├── students[]
    │   ├── user
    │   └── attendances[]
    │       └── lesson
    └── lessons[]
        ├── subject
        ├── teacher
        └── attendances[]
            └── student

InfoFile
    ├── student
    │   ├── user
    │   └── classRoom
    └── file_path (storage/app/public)
```

---

## 💾 Database Schema Relevant

### **class_rooms table:**

```sql
CREATE TABLE class_rooms (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    school_id BIGINT,
    name VARCHAR(50),
    grade INT (10, 11, atau 12),  -- 🔑 PENTING untuk filter Anak Bangau
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

INDEX: (grade)
```

### **students table:**

```sql
CREATE TABLE students (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    class_room_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

INDEX: (class_room_id)
```

### **info_files table:**

```sql
CREATE TABLE info_files (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    student_id BIGINT,
    school VARCHAR(120),
    class_name VARCHAR(50),
    subject VARCHAR(120),
    title VARCHAR(120),
    material VARCHAR(255),
    file_path VARCHAR(255),
    file_type VARCHAR(20),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

INDEX: (student_id)
```

### **lessons table:**

```sql
CREATE TABLE lessons (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    date DATE,
    class_room_id BIGINT,    -- 🔑 Link ke class untuk filter Anak Bangau
    subject_id BIGINT,
    teacher_id BIGINT,
    start_time TIME,
    end_time TIME,
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

INDEX: (class_room_id)
```

### **attendances table:**

```sql
CREATE TABLE attendances (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    lesson_id BIGINT,
    student_id BIGINT,         -- 🔑 Link ke student
    status VARCHAR(20),        -- 'hadir', 'izin', 'sakit', dll
    marked_by BIGINT,          -- Teacher ID
    marked_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

INDEX: (student_id, status)
INDEX: (lesson_id)
```

---

## 🔗 Eloquent Relationships

### **ClassRoom Model:**

```php
class ClassRoom extends Model {
    public function students() {
        return $this->hasMany(Student::class);
    }

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }
}
```

### **Student Model:**

```php
class Student extends Model {
    public function classRoom() {
        return $this->belongsTo(ClassRoom::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }

    public function infoFiles() {
        return $this->hasMany(InfoFile::class);
    }
}
```

### **Lesson Model:**

```php
class Lesson extends Model {
    public function classRoom() {
        return $this->belongsTo(ClassRoom::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
}
```

### **Attendance Model:**

```php
class Attendance extends Model {
    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
```

### **InfoFile Model:**

```php
class InfoFile extends Model {
    public function student() {
        return $this->belongsTo(Student::class);
    }
}
```

---

## 🎯 Query Examples

### **1. Get all files dari Anak Bangau:**

```php
$files = InfoFile::with(['student.user', 'student.classRoom'])
    ->whereHas('student.classRoom', function($query) {
        $query->whereIn('grade', [10, 11, 12]);
    })
    ->latest()
    ->paginate(20);
```

**Generated SQL:**

```sql
SELECT * FROM info_files
INNER JOIN students ON students.id = info_files.student_id
INNER JOIN class_rooms ON class_rooms.id = students.class_room_id
WHERE class_rooms.grade IN (10, 11, 12)
ORDER BY info_files.created_at DESC
LIMIT 20 OFFSET 0;
```

### **2. Get files dari specific class:**

```php
$files = InfoFile::whereHas('student', function($query) {
    $query->where('class_room_id', $classRoomId);
})
->with(['student.user', 'student.classRoom'])
->latest()
->paginate(20);
```

### **3. Get files dengan search subject:**

```php
$files = InfoFile::where('subject', 'like', '%' . $subject . '%')
    ->with(['student.user', 'student.classRoom'])
    ->latest()
    ->paginate(20);
```

### **4. Get classes (Anak Bangau only):**

```php
$classes = ClassRoom::whereIn('grade', [10, 11, 12])
    ->orderBy('grade')
    ->orderBy('name')
    ->get();

// Result:
// - id: 1, name: "IPA A", grade: 10
// - id: 2, name: "IPS B", grade: 10
// - id: 3, name: "IPA C", grade: 11
// - id: 4, name: "IPS D", grade: 12
```

### **5. Count total lessons (Anak Bangau):**

```php
$totalLessons = Lesson::whereHas('classRoom', function($query) {
    $query->whereIn('grade', [10, 11, 12]);
})->count();

// Result: 20 (misalnya, total jadwal pelajaran)
```

### **6. Count attendance for student:**

```php
$presentCount = Attendance::where('student_id', $studentId)
    ->whereIn('status', ['hadir', 'present', '1', 'Hadir'])
    ->count();

// Result: 17 (siswa hadir 17 kali)
```

### **7. Calculate attendance percentage:**

```php
$totalLessons = Lesson::whereHas('classRoom', function($query) {
    $query->whereIn('grade', [10, 11, 12]);
})->count();

$presentCount = Attendance::where('student_id', $studentId)
    ->whereIn('status', ['hadir', 'present', '1', 'Hadir'])
    ->count();

$percentage = ($presentCount / $totalLessons) * 100;
// Result: 85.0 (85%)
```

---

## 🌐 API Endpoints

### **1. View Student Files (Teacher):**

```
GET /teacher/dokumen
GET /teacher/dokumen?class_room_id=1
GET /teacher/dokumen?subject=Matematika
GET /teacher/dokumen?class_room_id=1&subject=Matematika&page=2
```

**Response:**

```json
{
    "files": [
        {
            "id": 1,
            "title": "Tugas MTK",
            "subject": "Matematika",
            "file_path": "info_files/...",
            "file_type": "PDF",
            "created_at": "2025-11-05T10:30:00",
            "student": {
                "id": 5,
                "user": {
                    "name": "Ahmad Kusuma"
                },
                "classRoom": {
                    "id": 1,
                    "name": "IPA A",
                    "grade": 10
                }
            }
        }
    ],
    "pagination": {
        "total": 45,
        "per_page": 20,
        "current_page": 1,
        "last_page": 3
    }
}
```

### **2. Download File:**

```
GET /info/download/{id}
```

**Response:**

-   File binary (streaming)
-   Content-Type: application/pdf (atau sesuai tipe file)

### **3. Helper Method - Get Attendance Percentage:**

```php
// Di controller
$attendance = $this->getAttendancePercentage($studentId);

// Return:
[
    'percentage' => 85.0,
    'present' => 17,
    'total' => 20,
    'formatted' => '85%'
]
```

---

## 🎨 Blade Template Helpers

### **Attendance Badge:**

```blade
@php
    $totalLessons = \App\Models\Lesson::whereHas('classRoom', function($q) {
        $q->whereIn('grade', [10, 11, 12]);
    })->count();

    $presentCount = \App\Models\Attendance::where('student_id', $file->student->id)
        ->whereIn('status', ['hadir', 'present', '1', 'Hadir'])
        ->count();

    $percentage = $totalLessons > 0
        ? round(($presentCount / $totalLessons) * 100, 1)
        : 0;
@endphp

@if($percentage >= 80)
    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
        ✓ {{ $percentage }}%
    </span>
@elseif($percentage >= 70)
    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
        ⚠ {{ $percentage }}%
    </span>
@else
    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
        ✗ {{ $percentage }}%
    </span>
@endif

<div class="text-xs text-gray-500 mt-1">
    ({{ $presentCount }}/{{ $totalLessons }} pertemuan)
</div>
```

---

## 🔍 Troubleshooting

### **Issue 1: Query tidak mengembalikan data (Kelas 10/11/12)**

**Solusi:**

```php
// Check apakah ada data dengan grade 10, 11, 12
$classes = ClassRoom::whereIn('grade', [10, 11, 12])->get();
dd($classes); // Debug

// Jika kosong, update manually:
ClassRoom::where('name', 'like', '%10%')->update(['grade' => 10]);
ClassRoom::where('name', 'like', '%11%')->update(['grade' => 11]);
ClassRoom::where('name', 'like', '%12%')->update(['grade' => 12]);
```

### **Issue 2: Presentase kehadiran selalu 0%**

**Solusi:**

```php
// Check apakah attendance records ada
$attendances = Attendance::where('student_id', $studentId)->get();
dd($attendances); // Debug

// Check status values
$statuses = Attendance::pluck('status')->unique();
dd($statuses); // Check actual status values

// Update query sesuai actual status
->whereIn('status', ['hadir', 'present', '1', 'Hadir', 'HADIR'])
```

### **Issue 3: Filter kelas tidak bekerja**

**Solusi:**

```php
// Check filter parameter
dd(request('class_room_id')); // Debug

// Check class_room_id exists
$class = ClassRoom::find(request('class_room_id'));
dd($class); // Should not be null

// Check relationship
$students = Student::where('class_room_id', request('class_room_id'))->get();
dd($students); // Should have data
```

### **Issue 4: Performance lambat**

**Solusi:**

```php
// Add indexes
Schema::table('class_rooms', function (Blueprint $table) {
    $table->index('grade');
});

Schema::table('attendances', function (Blueprint $table) {
    $table->index(['student_id', 'status']);
});

// Optimize queries with eager loading
$files = InfoFile::with([
    'student.user',
    'student.classRoom',
    'student.attendances'
])->get();

// Use select() untuk limit columns
$files = InfoFile::with([
    'student:id,user_id,class_room_id',
    'student.user:id,name',
    'student.classRoom:id,name,grade'
])->get();
```

---

## 📊 Sample Data (untuk Testing)

### **Seed Classes (Anak Bangau):**

```php
// database/seeders/AnakBangauSeeder.php
use Illuminate\Database\Seeder;
use App\Models\ClassRoom;

class AnakBangauSeeder extends Seeder {
    public function run() {
        ClassRoom::create(['school_id' => 1, 'name' => 'IPA A', 'grade' => 10]);
        ClassRoom::create(['school_id' => 1, 'name' => 'IPS B', 'grade' => 10]);
        ClassRoom::create(['school_id' => 1, 'name' => 'IPA C', 'grade' => 11]);
        ClassRoom::create(['school_id' => 1, 'name' => 'IPS D', 'grade' => 11]);
        ClassRoom::create(['school_id' => 1, 'name' => 'IPA E', 'grade' => 12]);
        ClassRoom::create(['school_id' => 1, 'name' => 'IPS F', 'grade' => 12]);
    }
}
```

### **Run Seeder:**

```bash
php artisan db:seed --class=AnakBangauSeeder
```

---

## 🧪 Unit Test Examples

```php
// tests/Feature/TeacherDocumentTest.php
use Tests\TestCase;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\InfoFile;

class TeacherDocumentTest extends TestCase {

    /** @test */
    public function teacher_can_view_student_files_from_anak_bangau() {
        $teacher = $this->createTeacher();
        $classRoom = ClassRoom::create(['grade' => 10, 'name' => 'IPA A']);
        $student = Student::create(['user_id' => ..., 'class_room_id' => $classRoom->id]);
        $file = InfoFile::create(['student_id' => $student->id, 'title' => 'Test']);

        $response = $this->actingAs($teacher)->get('/teacher/dokumen');
        $response->assertStatus(200);
        $response->assertSeeText('Test');
    }

    /** @test */
    public function filter_by_kelas_only_shows_anak_bangau() {
        $classRoom10 = ClassRoom::create(['grade' => 10, 'name' => 'IPA A']);
        $classRoom5 = ClassRoom::create(['grade' => 5, 'name' => 'V A']);

        $response = $this->actingAs($teacher)->get('/teacher/dokumen');
        $response->assertSeeText('IPA A');
        $response->assertDontSeeText('V A');
    }

    /** @test */
    public function student_filter_not_shown() {
        $response = $this->actingAs($teacher)->get('/teacher/dokumen');
        $response->assertDontSeeText('👤 Siswa');
    }

    /** @test */
    public function attendance_percentage_calculated_correctly() {
        // Setup...
        $percentage = $this->getAttendancePercentage($studentId);
        $this->assertEquals(85.0, $percentage);
    }
}
```

---

## 🚀 Deployment Checklist

-   [ ] Backup database
-   [ ] Run migrations (jika ada)
-   [ ] Seed Anak Bangau classes (grade 10, 11, 12)
-   [ ] Check `grade` column di `class_rooms`
-   [ ] Verify attendance data di database
-   [ ] Add database indexes:
    ```php
    Schema::table('class_rooms', function (Blueprint $table) {
        $table->index('grade');
    });
    ```
-   [ ] Run `php artisan cache:clear`
-   [ ] Run `php artisan route:clear`
-   [ ] Test filter kelas
-   [ ] Test attendance percentage
-   [ ] Test download file
-   [ ] Monitor performance (query time)
-   [ ] Setup error logging

---

## 📞 Support & Contact

**Issues:**

-   Filter tidak bekerja → Check database indices
-   Presentase 0% → Check attendance records & status values
-   Performance lambat → Add indices & optimize queries

**Documentation:**

-   Referensi Teknis: `UPDATE_DOKUMEN_SISWA_V3.md`
-   User Guide: `TEACHER_DOKUMEN_GUIDE.md`
-   API Reference: `API_REFERENCE.md`

---

**Version:** 3.1 - Referensi Teknis  
**Date:** November 5, 2025  
**Status:** 📚 DOCUMENTATION COMPLETE
