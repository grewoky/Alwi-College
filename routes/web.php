<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InfoFileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DashboardController;

// ============ HOME & DASHBOARD ============

// Halaman home
Route::get('/', function () {
    return view('welcome');
});

// Dashboard redirect sesuai role
Route::middleware('auth')->get('/dashboard', function () {
    $u = Auth::user();
    $roles = DB::table('model_has_roles')
        ->join('roles','roles.id','=','model_has_roles.role_id')
        ->where('model_has_roles.model_type', get_class($u))
        ->where('model_has_roles.model_id', $u->id)
        ->pluck('roles.name')->toArray();

    if (in_array('admin',$roles))   return redirect()->route('admin.dashboard');
    if (in_array('teacher',$roles)) return redirect()->route('teacher.dashboard');
    if (in_array('student',$roles)) return redirect()->route('student.dashboard');

    abort(403,'Role tidak dikenali');
})->name('dashboard');

// ============ ADMIN AREA ============
Route::middleware(['auth','role:admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class,'admin'])->name('admin.dashboard');
    
    // ADMIN JADWAL (Manage pelajaran, generate, edit, delete)
    Route::get('/jadwal', [LessonController::class,'adminDashboard'])->name('lessons.admin.dashboard');
    Route::get('/jadwal/list', [LessonController::class,'adminView'])->name('lessons.admin');
    Route::get('/jadwal/generate', [LessonController::class,'showGenerate'])->name('lessons.generate.form');
    Route::post('/jadwal/generate', [LessonController::class,'generate'])->name('lessons.generate');
    Route::get('/jadwal/{lesson}/edit', [LessonController::class,'editLesson'])->name('lessons.edit');
    Route::put('/jadwal/{lesson}', [LessonController::class,'updateLesson'])->name('lessons.update');
    Route::delete('/jadwal/{lesson}', [LessonController::class,'deleteLesson'])->name('lessons.destroy');
    Route::get('/jadwal/logs/deleted', [LessonController::class,'showDeletedLog'])->name('lessons.logs.deleted');
    Route::get('/jadwal/logs/expired', [LessonController::class,'showExpiredLessons'])->name('lessons.logs.expired');
    
    // ADMIN INFO (Lihat file yang diupload siswa)
    Route::get('/info', [InfoFileController::class,'listAll'])->name('info.admin.list');
    Route::get('/info/options', [InfoFileController::class,'showDownloadOptions'])->name('info.download.options');
    Route::get('/info/{info}/download', [InfoFileController::class,'download'])->name('info.download');
    Route::get('/info/{info}/download-details', [InfoFileController::class,'downloadWithDetails'])->name('info.download.details');
    Route::post('/info/download-by-type', [InfoFileController::class,'downloadByType'])->name('info.download.by-type');
    Route::post('/info/download-selected', [InfoFileController::class,'downloadSelected'])->name('info.download.selected');
    Route::get('/info/download-all/zip', [InfoFileController::class,'downloadAll'])->name('info.downloadAll');
    Route::get('/info/stats', [InfoFileController::class,'getFileStats'])->name('info.stats');
    Route::delete('/info/{info}', [InfoFileController::class,'destroy'])->name('info.destroy');
    
    // ADMIN TRIP GURU (Track trip guru)
    Route::get('/trips', [TripController::class,'index'])->name('trips.index');
    Route::get('/trips/{teacher}', [TripController::class,'show'])->name('trips.show');
    Route::post('/trips/{teacher}', [TripController::class,'store'])->name('trips.store');
    Route::put('/trips/{trip}', [TripController::class,'update'])->name('trips.update');
    Route::delete('/trips/{trip}', [TripController::class,'destroy'])->name('trips.destroy');
    
    // ADMIN PEMBAYARAN (Verifikasi pembayaran)
    Route::get('/payments', [PaymentController::class,'listAll'])->name('pay.list');
    Route::post('/payments/{payment}/verify', [PaymentController::class,'verify'])->name('pay.verify');
    Route::delete('/payments/{payment}', [PaymentController::class,'destroy'])->name('pay.destroy');
    
    // ADMIN ABSENSI (Lihat laporan kehadiran siswa)
    Route::get('/attendance', [AttendanceController::class,'adminView'])->name('attendance.admin');
    Route::get('/attendance/report', [AttendanceController::class,'report'])->name('attendance.report');
});

// ============ TEACHER AREA ============
Route::middleware(['auth','role:teacher'])->prefix('teacher')->group(function () {
    // Dashboard
    Route::get('/teacher', [DashboardController::class,'teacher'])->name('teacher.dashboard');
    
    // TEACHER JADWAL (Lihat jadwal mengajar, absensi)
    Route::get('/jadwal', [LessonController::class,'teacherView'])->name('lessons.teacher');
    Route::get('/attendance', [AttendanceController::class,'teacherView'])->name('attendance.teacher');
    Route::get('/mark-attendance/{classRoom}', [AttendanceController::class,'markAttendance'])->name('attendance.mark');
    Route::post('/mark-attendance/{classRoom}', [AttendanceController::class,'storeMarkAttendance'])->name('attendance.store.mark');
    
    // TEACHER INFO (Lihat file yang diupload siswa)
    Route::get('/dokumen', [InfoFileController::class,'teacherViewStudentFiles'])->name('info.teacher.student-files');
});

// ============ STUDENT AREA ============
Route::middleware(['auth','role:student'])->prefix('student')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class,'student'])->name('student.dashboard');
    
    // STUDENT JADWAL (Lihat jadwal pelajaran)
    Route::get('/jadwal', [LessonController::class,'studentView'])->name('lessons.student');
    
    // STUDENT INFO (Upload file pribadi)
    Route::get('/info', [InfoFileController::class,'index'])->name('info.index');
    Route::post('/info', [InfoFileController::class,'store'])->name('info.store');
    
    // STUDENT PEMBAYARAN (Upload bukti pembayaran)
    Route::get('/payment', [PaymentController::class,'index'])->name('pay.index');
    Route::post('/payment', [PaymentController::class,'store'])->name('pay.store');
    
    // STUDENT ABSENSI (Lihat kehadiran)
    Route::get('/attendance', [AttendanceController::class,'studentView'])->name('attendance.student');
});

// ============ UNIVERSAL REDIRECTS ============
// Untuk backward compatibility, redirect routes lama ke route baru sesuai role

// Redirect /jadwal ke route sesuai role
Route::middleware('auth')->get('/jadwal', function () {
    $user = Auth::user();
    $roles = DB::table('model_has_roles')
        ->join('roles','roles.id','=','model_has_roles.role_id')
        ->where('model_has_roles.model_type', get_class($user))
        ->where('model_has_roles.model_id', $user->id)
        ->pluck('roles.name')->toArray();
    
    if (in_array('admin', $roles)) return redirect()->route('lessons.admin.dashboard');
    if (in_array('teacher', $roles)) return redirect()->route('lessons.teacher');
    if (in_array('student', $roles)) return redirect()->route('lessons.student');
    
    abort(403, 'Unauthorized');
});

// Redirect /attendance ke route sesuai role
Route::middleware('auth')->get('/attendance', function () {
    $user = Auth::user();
    $roles = DB::table('model_has_roles')
        ->join('roles','roles.id','=','model_has_roles.role_id')
        ->where('model_has_roles.model_type', get_class($user))
        ->where('model_has_roles.model_id', $user->id)
        ->pluck('roles.name')->toArray();
    
    if (in_array('student', $roles)) return redirect()->route('attendance.student');
    if (in_array('teacher', $roles)) return redirect()->route('attendance.teacher');
    if (in_array('admin', $roles)) return redirect()->route('attendance.teacher');
    
    abort(403, 'Unauthorized');
});

// ============ PROFILE & AUTH ============

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';
