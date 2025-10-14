<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;          // ⬅️ pakai Facade
use Illuminate\Support\Facades\DB;            // ⬅️ pakai Facade
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\InfoFileController;
use App\Http\Controllers\ProfileController;


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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';
