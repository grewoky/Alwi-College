<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ⬅️ penting
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Lesson;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;

class LessonController extends Controller
{
    public function showGenerate()
    {
        $teachersList = Teacher::with('user')->orderBy('id')->get();
        $subjectsList = Subject::orderBy('name')->get();
        
        return view('lessons.generate', [
            'teachersList' => $teachersList,
            'subjectsList' => $subjectsList,
        ]);
    }

    public function generate(Request $r)
    {
        // Validasi input
        $r->validate([
            'school'     => 'required|in:Negeri,IGS,Xavega,Bangau',
            'grade'      => 'required|in:10,11,12',
            'room_code'  => 'required|string|max:10',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
        ]);

        try {
            // Cari ClassRoom berdasarkan grade dan room_code
            $classRoom = ClassRoom::where('grade', (int)$r->grade)
                ->where('name', 'like', '%' . $r->room_code . '%')
                ->first();

            if (!$classRoom) {
                return back()
                    ->withErrors(['room_code' => "Ruangan '{$r->room_code}' tidak ditemukan untuk Kelas {$r->grade}"])
                    ->withInput();
            }

            // Validasi time jika keduanya diisi
            if ($r->start_time && $r->end_time) {
                if ($r->start_time >= $r->end_time) {
                    return back()
                        ->withErrors(['end_time' => 'Jam selesai harus lebih besar dari jam mulai'])
                        ->withInput();
                }
            }

            $start = Carbon::parse($r->start_date);
            $end = Carbon::parse($r->end_date);

            $created = 0;
            $skipped = 0;
            $errors = [];

            DB::transaction(function() use ($r, $classRoom, $start, $end, &$created, &$skipped, &$errors) {
                for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    // Check if lesson already exists
                    $existingLesson = Lesson::where('date', $d->toDateString())
                        ->where('class_room_id', $classRoom->id)
                        ->where('teacher_id', $r->teacher_id)
                        ->first();
                    
                    if (!$existingLesson) {
                        try {
                            Lesson::create([
                                'date'          => $d->toDateString(),
                                'class_room_id' => $classRoom->id,
                                'teacher_id'    => $r->teacher_id,
                                'subject_id'    => $r->subject_id,
                                'start_time'    => $r->start_time,
                                'end_time'      => $r->end_time,
                                'description'   => $r->description,
                            ]);
                            $created++;
                        } catch (\Exception $e) {
                            $errors[] = "Error pada tanggal {$d->format('d-m-Y')}: {$e->getMessage()}";
                        }
                    } else {
                        $skipped++;
                    }
                }
            });

            if (count($errors) > 0) {
                Log::warning('Lesson generation dengan error', ['errors' => $errors]);
                $errorMsg = implode(', ', array_slice($errors, 0, 3)); // Tampilkan 3 error pertama
                return back()->with('warning', "⚠️ Jadwal dibuat dengan error: $errorMsg");
            }

            $msg = "✅ Jadwal berhasil digenerate! {$created} jadwal baru dibuat, {$skipped} duplikat diabaikan. Ruangan: {$classRoom->name} (Kelas {$classRoom->grade}), Tanggal: {$start->format('d M Y')} - {$end->format('d M Y')}";
            
            Log::info('Lesson generation success', [
                'created' => $created,
                'skipped' => $skipped,
                'classroom_id' => $classRoom->id,
                'classroom_name' => $classRoom->name,
                'teacher_id' => $r->teacher_id,
                'date_range' => $start->format('Y-m-d') . ' to ' . $end->format('Y-m-d'),
            ]);

            return back()->with('ok', $msg);
        } catch (\Exception $e) {
            Log::error('Lesson generation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withErrors(['general' => 'Gagal membuat jadwal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function index(Request $r)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail(); // ⬅️ fix di sini

        $q = Lesson::with(['classRoom.school','subject'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('date','desc');

        if ($r->filled('school_id')) $q->whereHas('classRoom', fn($qq)=>$qq->where('school_id',$r->school_id));
        if ($r->filled('grade'))     $q->whereHas('classRoom', fn($qq)=>$qq->where('grade',$r->grade));
        if ($r->filled('date'))      $q->where('date',$r->date);

        $lessons = $q->paginate(15)->withQueryString();
        
        // ✅ Get only Kelas 10, 11, 12
        $classes = ClassRoom::whereIn('grade', [10, 11, 12])
            ->orderBy('grade')
            ->get();

        return view('lessons.teacher_list', [
            'lessons' => $lessons,
            'classes' => $classes,
            'filters' => [
                'school_id' => $r->school_id,
                'grade'     => $r->grade,
                'date'      => $r->date,
            ],
        ]);
    }

    // View jadwal untuk student
    public function studentView(Request $r)
    {
        $user = Auth::user();
        $student = \App\Models\Student::where('user_id', $user->id)->firstOrFail();
        
        // Get lessons for student's class
        $q = Lesson::with(['teacher.user', 'subject', 'classRoom'])
            ->where('class_room_id', $student->class_room_id)
            ->orderBy('date', 'asc');
        
        // Filter by grade if provided
        if ($r->filled('grade')) {
            $q->whereHas('classRoom', function($query) use ($r) {
                $query->where('grade', $r->grade);
            });
        }
        
        if ($r->filled('date')) {
            $q->whereDate('date', $r->date);
        }
        
        $lessons = $q->paginate(15)->withQueryString();
        
        // ✅ Get only Kelas 10, 11, 12
        $classes = ClassRoom::whereIn('grade', [10, 11, 12])
            ->orderBy('grade')
            ->get();
        
        return view('lessons.student-view', compact('student', 'lessons', 'classes'));
    }

    // View jadwal untuk admin/guru
    public function adminView(Request $r)
    {
        $q = Lesson::with(['teacher.user', 'subject', 'classRoom'])
            ->orderBy('date', 'desc');
        
        if ($r->filled('teacher_id')) {
            $q->where('teacher_id', $r->teacher_id);
        }
        
        if ($r->filled('class_room_id')) {
            $q->where('class_room_id', $r->class_room_id);
        }
        
        if ($r->filled('date')) {
            $q->whereDate('date', $r->date);
        }
        
        $lessons = $q->paginate(20)->withQueryString();
        $teachers = Teacher::with('user')->orderBy('id')->get();
        // ✅ Get only Kelas 10, 11, 12
        $classes = ClassRoom::whereIn('grade', [10, 11, 12])
            ->orderBy('grade')
            ->get();
        
        return view('lessons.admin-view', compact(
            'lessons',
            'teachers',
            'classes'
        ));
    }

    // Edit jadwal individual
    public function editLesson(Lesson $lesson)
    {
        $subjectsList = Subject::orderBy('name')->get();
        return view('lessons.edit', compact('lesson', 'subjectsList'));
    }

    // Update jadwal individual
    public function updateLesson(Lesson $lesson, Request $r)
    {
        // Enhanced validation
        $r->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
        ]);
        
        // Validate time logic if both times provided
        if ($r->filled('start_time') && $r->filled('end_time')) {
            if ($r->start_time >= $r->end_time) {
                return back()
                    ->withInput()
                    ->withErrors(['end_time' => 'Jam selesai harus lebih besar dari jam mulai']);
            }
        }
        
        try {
            $lesson->update([
                'subject_id' => $r->subject_id,
                'start_time' => $r->start_time,
                'end_time'   => $r->end_time,
            ]);
            
            return back()->with('ok', 'Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
           
            return back()->with('error', 'Gagal memperbarui jadwal');
        }
    }

    // Delete jadwal individual
    public function deleteLesson(Lesson $lesson)
    {
        try {
            $lessonInfo = [
                'date' => $lesson->date,
                'class' => $lesson->classRoom->name ?? 'Unknown',
                'teacher' => $lesson->teacher->user->name ?? 'Unknown',
            ];
            
            $lesson->delete();
            
           
            
            return back()->with('ok', 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
           
            return back()->with('error', 'Gagal menghapus jadwal');
        }
    }

    // Admin dashboard - jadwal yang di-upload
    public function adminDashboard()
    {
        // Statistik jadwal total
        $totalLessons = Lesson::count();
        $totalTeachers = Lesson::distinct('teacher_id')->count();
        $totalClasses = Lesson::distinct('class_room_id')->count();
        
        // Jadwal per status
        $lessonsWithoutTime = Lesson::whereNull('start_time')->orWhereNull('end_time')->count();
        
        // Jadwal terbaru
        $recentLessons = Lesson::with(['teacher.user', 'subject', 'classRoom'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Jadwal per guru
        $teachersLessonCount = Teacher::withCount(['lessons'])->orderBy('lessons_count', 'desc')->get();

        return view('lessons.admin-dashboard', compact(
            'totalLessons',
            'totalTeachers',
            'totalClasses',
            'lessonsWithoutTime',
            'recentLessons',
            'teachersLessonCount'
        ));
    }

    // View jadwal untuk teacher (hanya kelas yang dia ajar)
    public function teacherView(Request $r)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->firstOrFail();
        
        // Get lessons hanya untuk guru yang login
        $q = Lesson::with(['subject', 'classRoom.school'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('date', 'desc');
        
        // Filter by grade if provided
        if ($r->filled('grade')) {
            $q->whereHas('classRoom', function($query) use ($r) {
                $query->where('grade', $r->grade);
            });
        }
        
        if ($r->filled('date')) {
            $q->whereDate('date', $r->date);
        }
        
        if ($r->filled('class_room_id')) {
            $q->where('class_room_id', $r->class_room_id);
        }
        
        $lessons = $q->paginate(20)->withQueryString();
        
        // Get classes yang diajari guru ini untuk filter
        $teacherClasses = ClassRoom::whereIn('id', function($query) use ($teacher) {
            $query->select('class_room_id')
                  ->from('lessons')
                  ->where('teacher_id', $teacher->id)
                  ->distinct();
        })->orderBy('name')->get();
        
        return view('lessons.teacher-view', compact(
            'teacher',
            'lessons',
            'teacherClasses'
        ));
    }

    /**
     * Menampilkan jadwal yang akan dihapus (jadwal yang sudah lewat tanggalnya)
     * GET /admin/jadwal/will-delete
     */
    public function showExpiredLessons()
    {
        $today = Carbon::now()->startOfDay();

        // Ambil jadwal yang date < hari ini
        $expiredLessons = Lesson::with(['classRoom.school', 'teacher.user', 'subject'])
            ->where('date', '<', $today->toDateString())
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('lessons.expired', [
            'expiredLessons' => $expiredLessons,
            'totalExpired' => Lesson::where('date', '<', $today->toDateString())->count(),
        ]);
    }

    /**
     * Menampilkan log jadwal yang telah dihapus
     * GET /admin/jadwal/delete-log
     */
    public function showDeletedLog()
    {
        // Check if table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('deleted_lessons_log')) {
            return back()->withErrors('Tabel deleted_lessons_log belum dibuat. Jalankan: php artisan migrate');
        }

        $deletedLog = DB::table('deleted_lessons_log')
            ->orderBy('deleted_at', 'desc')
            ->paginate(30);

        return view('lessons.deleted-log', [
            'deletedLog' => $deletedLog,
            'totalDeleted' => DB::table('deleted_lessons_log')->count(),
        ]);
    }

    /**
     * Manual delete - hapus jadwal tertentu (admin only)
     * DELETE /admin/jadwal/{id}
     */
    public function destroyManual($id)
    {
        $lesson = Lesson::findOrFail($id);

        // Log ke deleted_lessons_log
        if (\Illuminate\Support\Facades\Schema::hasTable('deleted_lessons_log')) {
            DB::table('deleted_lessons_log')->insert([
                'lesson_date' => $lesson->date,
                'classroom_id' => $lesson->class_room_id,
                'teacher_id' => $lesson->teacher_id,
                'subject_id' => $lesson->subject_id,
                'start_time' => $lesson->start_time,
                'end_time' => $lesson->end_time,
                'deleted_by' => Auth::user()->email ?? 'admin',
                'deletion_reason' => 'Manual deletion by admin',
                'deleted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $lesson->delete();

        Log::info('Lesson manually deleted', [
            'lesson_id' => $id,
            'date' => $lesson->date,
            'deleted_by' => Auth::user()->email,
        ]);

        return back()->with('ok', '✅ Jadwal berhasil dihapus');
    }
}

