<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;          // ⬅️ pakai Facade
use Illuminate\Support\Facades\DB;            // ⬅️ pakai Facade
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\InfoFileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\LessonController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DashboardController;



// Halaman welcome
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Contoh rute tes role
Route::middleware(['auth','role:admin'])->get('/admin-only', fn()=> 'ADMIN OK');
Route::middleware(['auth','role:teacher|admin'])->get('/teacher-or-admin', fn()=> 'TEACHER/ADMIN OK');
Route::middleware(['auth','role:student'])->get('/student-only', fn()=> 'STUDENT OK');

// DEBUG master data + roles (pakai Facade agar aman)
Route::middleware('auth')->get('/debug/master', function () {
    $u = Auth::user();
    if (!$u) {
        return [
            'schools'  => \App\Models\School::count(),
            'classes'  => \App\Models\ClassRoom::count(),
            'subjects' => \App\Models\Subject::count(),
            'teachers' => \App\Models\Teacher::count(),
            'students' => \App\Models\Student::count(),
            'my_roles' => [],
            'note'     => 'not authenticated',
        ];
    }

    $roles = DB::table('model_has_roles')
        ->join('roles','roles.id','=','model_has_roles.role_id')
        ->where('model_has_roles.model_type', get_class($u))
        ->where('model_has_roles.model_id', $u->id)
        ->pluck('roles.name');

    return [
        'schools'  => \App\Models\School::count(),
        'classes'  => \App\Models\ClassRoom::count(),
        'subjects' => \App\Models\Subject::count(),
        'teachers' => \App\Models\Teacher::count(),
        'students' => \App\Models\Student::count(),
        'my_roles' => $roles,
    ];
});

// ================== ROUTE INFO ==================

// Redirect universal: /dashboard -> ke dashboard sesuai role
Route::middleware('auth')->get('/dashboard', function () {
    $u = \Illuminate\Support\Facades\Auth::user();
    $roles = \Illuminate\Support\Facades\DB::table('model_has_roles')
        ->join('roles','roles.id','=','model_has_roles.role_id')
        ->where('model_has_roles.model_type', get_class($u))
        ->where('model_has_roles.model_id', $u->id)
        ->pluck('roles.name')->toArray();

    if (in_array('admin',$roles))   return redirect()->route('admin.dashboard');
    if (in_array('teacher',$roles)) return redirect()->route('teacher.dashboard');
    if (in_array('student',$roles)) return redirect()->route('student.dashboard');

    abort(403,'Role tidak dikenali');
})->name('dashboard');

// ADMIN area
Route::middleware(['auth','role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class,'admin'])->name('admin.dashboard');
    // (sudah ada sebelumnya)
    Route::get('/lessons/generate', [\App\Http\Controllers\LessonController::class,'showGenerate'])->name('lessons.generate.form');
    Route::post('/lessons/generate', [\App\Http\Controllers\LessonController::class,'generate'])->name('lessons.generate');
    Route::get('/trips', [\App\Http\Controllers\TripController::class,'index'])->name('trips.index');
    Route::get('/payments', [\App\Http\Controllers\PaymentController::class,'listAll'])->name('pay.list');
});

// TEACHER area
Route::middleware(['auth','role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/', [DashboardController::class,'teacher'])->name('teacher.dashboard');
    Route::get('/lessons', [\App\Http\Controllers\LessonController::class,'index'])->name('teacher.lessons');
    Route::get('/lessons/{lesson}', [\App\Http\Controllers\AttendanceController::class,'show'])->name('attendance.show');
    Route::post('/lessons/{lesson}', [\App\Http\Controllers\AttendanceController::class,'store'])->name('attendance.store');
});

// STUDENT area
Route::middleware(['auth','role:student'])->prefix('student')->group(function () {
    Route::get('/', [DashboardController::class,'student'])->name('student.dashboard');
    Route::get('/info', [\App\Http\Controllers\InfoFileController::class,'index'])->name('info.index');
    Route::post('/info', [\App\Http\Controllers\InfoFileController::class,'store'])->name('info.store');
    Route::get('/payment', [\App\Http\Controllers\PaymentController::class,'index'])->name('pay.index');
    Route::post('/payment', [\App\Http\Controllers\PaymentController::class,'store'])->name('pay.store');
});

// SISWA: upload bukti + riwayat
Route::middleware(['auth','role:student'])->group(function(){
    Route::get('/payment', [PaymentController::class,'index'])->name('pay.index');
    Route::post('/payment', [PaymentController::class,'store'])->name('pay.store');
});

// ADMIN: daftar & verifikasi
Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/admin/payments', [PaymentController::class,'listAll'])->name('pay.list');
    Route::post('/admin/payments/{payment}/verify', [PaymentController::class,'verify'])->name('pay.verify');
    Route::delete('/admin/payments/{payment}', [PaymentController::class,'destroy'])->name('pay.destroy');
});

// SISWA (upload & lihat file miliknya)
Route::middleware(['auth','role:student'])->group(function(){
    Route::get('/info', [InfoFileController::class,'index'])->name('info.index');
    Route::post('/info', [InfoFileController::class,'store'])->name('info.store');
});

// GURU & ADMIN (lihat semua + download)
Route::middleware(['auth','role:teacher|admin'])->group(function(){
    Route::get('/info/all', [InfoFileController::class,'listAll'])->name('info.list');
    Route::get('/info/{info}/download', [InfoFileController::class,'download'])->name('info.download');
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::delete('/info/{info}', [InfoFileController::class,'destroy'])->name('info.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ADMIN
Route::middleware(['auth','role:admin'])->group(function(){
  Route::get('/admin/lessons/generate', [LessonController::class,'showGenerate'])->name('lessons.generate.form');
  Route::post('/admin/lessons/generate', [LessonController::class,'generate'])->name('lessons.generate');
  Route::get('/admin/trips', [TripController::class,'index'])->name('trips.index');
});

// GURU
Route::middleware(['auth','role:teacher'])->group(function(){
  Route::get('/teacher/lessons', [LessonController::class,'index'])->name('teacher.lessons');
  Route::get('/teacher/lessons/{lesson}', [AttendanceController::class,'show'])->name('attendance.show');
  Route::post('/teacher/lessons/{lesson}', [AttendanceController::class,'store'])->name('attendance.store');
});

// SISWA
Route::middleware(['auth','role:student'])->get('/student/attendance', [AttendanceController::class,'studentSummary'])->name('student.attendance');


// Auth routes (Breeze)
require __DIR__.'/auth.php';
