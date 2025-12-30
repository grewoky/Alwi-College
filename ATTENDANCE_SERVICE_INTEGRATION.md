# 🔌 Integration Guide - Menggunakan AttendanceService di Aplikasi

## 📌 Overview

`AttendanceService` adalah centralized business logic untuk semua operasi attendance. Berikut cara menggunakannya di berbagai bagian aplikasi.

---

## 🎯 Dependency Injection

### Cara 1: Constructor Injection (Best Practice)

```php
<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;

class MyController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function myMethod()
    {
        // Gunakan service di sini
        $this->attendanceService->recordAttendance(...);
    }
}
```

### Cara 2: Method Injection

```php
public function myMethod(AttendanceService $service)
{
    // Service auto-inject oleh Laravel
    $service->recordAttendance(...);
}
```

### Cara 3: Service Container

```php
$service = app(AttendanceService::class);
$service->recordAttendance(...);
```

---

## 💡 Method Examples

### 1. Record Attendance + Auto-Update Tracker

```php
// Saat guru mark attendance
$this->attendanceService->recordAttendance(
    lessonId: 123,
    studentId: 45,
    status: 'present',        // atau 'alpha', 'izin', 'sakit'
    markedBy: Auth::id()
);

// Return: Attendance model
// Side effect: AttendanceTracker diupdate + auto-reset jika perlu
```

### 2. Get Monthly Statistics per Siswa

```php
$stats = $this->attendanceService->getStudentMonthlyStats(studentId: 45);

// Return:
// [
//     'hadir' => 15,
//     'alpha' => 2,
//     'izin' => 1,
//     'sakit' => 0,
//     'total' => 18,
// ]
```

### 3. Get Complete Tracking Summary

```php
$summary = $this->attendanceService->getStudentAttendanceSummary(studentId: 45);

// Return:
// [
//     'student' => Student object,
//     'tracker' => AttendanceTracker object,
//     'monthly_stats' => [...],
//     'counter_30_days' => 15,
//     'period_start_date' => Carbon object,
//     'days_remaining' => 15,
//     'historical_records' => ['2025-12' => 30, '2025-11' => 28],
// ]
```

### 4. Get Attendance Data untuk Report

```php
$filters = [
    'month' => 12,
    'year' => 2025,
    'school_id' => 1,
    'class_room_id' => 5,
];

$attendances = $this->attendanceService->getAttendanceDataForExport($filters);

// Return: Collection of Attendance models dengan relasi lengkap
foreach ($attendances as $attendance) {
    echo $attendance->student->user->name;
    echo $attendance->status;
    echo $attendance->student->attendanceTracker->attendance_count;
}
```

### 5. Export ke CSV

```php
// Option 1: Auto-generate dengan default filters
$csv = $this->attendanceService->exportToCSV();
// Return: Array with 'filename' dan 'content_type'

// Option 2: Dengan specific attendances
$attendances = Attendance::where(...)->get();
$csv = $this->attendanceService->exportToCSV($attendances, 'custom_name.csv');

// Option 3: Di controller dengan streaming
return response()->streamDownload(function () use ($attendances) {
    // ... generate CSV headers
    // ... loop dan fputcsv
}, 'attendance.csv');
```

### 6. Manual Reset Tracker

```php
$tracker = AttendanceTracker::find(1);
$tracker->resetCounter();

// Side effect:
// - counter = 0
// - period_start_date = now()
// - monthly_records updated
```

---

## 📋 Use Cases & Examples

### Use Case 1: Student Portal - Lihat Tracking Saya

```php
// StudentController.php
public function viewMyTracking(AttendanceService $service)
{
    $student = Student::find(auth()->user()->student_id);
    $summary = $service->getStudentAttendanceSummary($student->id);

    return view('student.tracking', [
        'counter' => $summary['counter_30_days'],
        'remaining' => $summary['days_remaining'],
        'period_start' => $summary['period_start_date'],
        'monthly_stats' => $summary['monthly_stats'],
        'history' => $summary['historical_records'],
    ]);
}
```

### Use Case 2: Dashboard - Siswa Mendekati Reset

```php
// StudentController.php
public function dashboard(AttendanceService $service)
{
    $student = Student::find(auth()->user()->student_id);
    $summary = $service->getStudentAttendanceSummary($student->id);

    $isClose = $summary['counter_30_days'] >= 25;
    $message = $isClose ? "Tinggal " . $summary['days_remaining'] . " hari lagi!" : null;

    return view('student.dashboard', compact('message', 'summary'));
}
```

### Use Case 3: Report Generator - Monthly Report

```php
// ReportController.php
public function generateMonthlyReport(
    Request $request,
    AttendanceService $service
) {
    $month = $request->input('month', now()->month);
    $year = $request->input('year', now()->year);

    $filters = [
        'month' => $month,
        'year' => $year,
        'school_id' => $request->input('school_id'),
    ];

    $attendances = $service->getAttendanceDataForExport($filters);

    // Generate PDF atau HTML report
    $totalRecords = $attendances->count();
    $presentCount = $attendances->where('status', 'present')->count();

    return view('reports.monthly', compact(
        'attendances',
        'totalRecords',
        'presentCount',
        'month',
        'year'
    ));
}
```

### Use Case 4: API - Get Student Tracking (JSON)

```php
// api/StudentController.php
public function getTracking(
    Request $request,
    AttendanceService $service
)
{
    $studentId = $request->input('student_id');

    $summary = $service->getStudentAttendanceSummary($studentId);

    return response()->json([
        'success' => true,
        'data' => [
            'counter' => $summary['counter_30_days'],
            'period_start' => $summary['period_start_date'],
            'days_remaining' => $summary['days_remaining'],
            'monthly_stats' => $summary['monthly_stats'],
        ]
    ]);
}
```

### Use Case 5: Cron Job - Weekly Notification

```php
// app/Console/Commands/NotifyAttendanceReminder.php
protected function handle(AttendanceService $service)
{
    $students = Student::with('attendanceTracker')->get();

    foreach ($students as $student) {
        $summary = $service->getStudentAttendanceSummary($student->id);

        // Notify jika mendekati 30 hari
        if ($summary['counter_30_days'] >= 25) {
            Notification::send($student->user, new AttendanceReminder($summary));
        }
    }
}
```

---

## 🎯 Integration Points

### 1. Di Controller

```php
class AttendanceController
{
    public function __construct(AttendanceService $service) {}

    // Gunakan service di method
    public function store(Request $request, AttendanceService $service) {}
}
```

### 2. Di Model Observer

```php
class AttendanceObserver
{
    public function created(Attendance $attendance)
    {
        $service = app(AttendanceService::class);
        $service->updateAttendanceTracker($attendance->student_id);
    }
}
```

### 3. Di Job/Queue

```php
class ProcessAttendanceJob implements ShouldQueue
{
    public function handle(AttendanceService $service)
    {
        // Process attendance records
        $service->recordAttendance(...);
    }
}
```

### 4. Di Blade Template

```blade
@php
    $service = app(App\Services\AttendanceService::class);
    $summary = $service->getStudentAttendanceSummary($studentId);
@endphp

<div>
    Hadir: {{ $summary['counter_30_days'] }}/30 hari
    Sisa: {{ $summary['days_remaining'] }} hari
</div>
```

### 5. Di Route Model Binding

```php
Route::get('/student/{student}/tracking', function (Student $student) {
    $service = app(AttendanceService::class);
    $data = $service->getStudentAttendanceSummary($student->id);
    return response()->json($data);
});
```

---

## 🔄 Error Handling

### Try-Catch Pattern

```php
try {
    $summary = $this->attendanceService->getStudentAttendanceSummary($studentId);
} catch (\Exception $e) {
    \Log::error('Attendance service error: ' . $e->getMessage());
    return back()->with('error', 'Terjadi kesalahan saat mengambil data absensi');
}
```

### Validation Pattern

```php
if (!$student = Student::find($studentId)) {
    throw new \InvalidArgumentException('Student not found');
}

if (!$student->attendanceTracker) {
    // Create tracker jika belum ada
    AttendanceTracker::create([
        'student_id' => $studentId,
        'attendance_count' => 0,
        'period_start_date' => now(),
    ]);
}
```

---

## 📊 Performance Optimization

### 1. Eager Loading

```php
$attendances = $this->attendanceService->getAttendanceDataForExport($filters);
// Sudah include relasi: student, lesson, marker, attendanceTracker

// Jangan:
foreach ($attendances as $a) {
    echo $a->student->user->name; // N+1 query!
}

// Lakukan:
$attendances->load('student.user', 'student.attendanceTracker');
```

### 2. Pagination untuk Large Dataset

```php
$attendances = Attendance::with([...])
    ->paginate(100); // Jangan load semua sekaligus
```

### 3. Indexing

```php
// Database sudah punya index:
- attendance_count
- period_start_date

// Query akan cepat
AttendanceTracker::where('attendance_count', '>', 25)->get();
```

---

## 🧪 Testing Service

### Unit Test

```php
class AttendanceServiceTest extends TestCase
{
    public function test_record_attendance_increments_counter()
    {
        $service = new AttendanceService();
        $studentId = 1;

        // Initial state
        $tracker = AttendanceTracker::where('student_id', $studentId)->first();
        $initialCount = $tracker->attendance_count;

        // Action
        $service->recordAttendance(1, $studentId, 'present', 1);

        // Assert
        $tracker->refresh();
        $this->assertEquals($initialCount + 1, $tracker->attendance_count);
    }

    public function test_auto_reset_at_30()
    {
        $service = new AttendanceService();

        // Setup: count = 29
        $tracker = AttendanceTracker::find(1);
        $tracker->update(['attendance_count' => 29]);

        // Action: mark hadir
        $service->recordAttendance(1, 1, 'present', 1);

        // Assert: counter reset
        $tracker->refresh();
        $this->assertEquals(0, $tracker->attendance_count);
        $this->assertNotNull($tracker->monthly_records);
    }
}
```

---

## 📌 Best Practices

1. **Selalu gunakan service untuk attendance logic**

    - Jangan direct query Attendance model
    - Semua business logic terpusat di service

2. **Inject service via constructor**

    - Lebih testable
    - Lebih clean
    - Laravel auto-resolve dependencies

3. **Handle exceptions gracefully**

    - Try-catch di controller
    - Log error untuk debugging
    - Show user-friendly message

4. **Use eager loading**

    - Avoid N+1 queries
    - Load relasi yang diperlukan
    - Performance matters!

5. **Cache results jika perlu**
    ```php
    $summary = Cache::remember(
        "student_tracking_{$studentId}",
        3600, // 1 jam
        fn() => $service->getStudentAttendanceSummary($studentId)
    );
    ```

---

**✅ Selesai!**

Service sudah siap digunakan di seluruh aplikasi. Referensikan dokumentasi ini saat butuh integrasi service di bagian lain!
