<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ⬅️ penting
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Lesson;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Subject;
use App\Models\Teacher;

class LessonController extends Controller
{
    public function showGenerate()
    {
        $teachersList = Teacher::with('user')->orderBy('id')->get();
        $subjectsList = Subject::orderBy('name')->get();
        
        return view('lessons.admin.generate', [
            'teachersList' => $teachersList,
            'subjectsList' => $subjectsList,
        ]);
    }

    public function generate(Request $r)
    {
        // Validasi input
        $r->validate([
            'school'     => 'required|in:Negeri,IGS,Xavega,Bangau,Kumbang',
            'grade'      => 'required|in:10,11,12',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'per_variant' => 'nullable',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
        ]);

        try {
            // Pastikan sekolah tersedia atau buat baru bila belum ada
            $school = School::firstOrCreate(['name' => $r->school]);

            // Ambil kelas berdasarkan sekolah & grade, buat default jika belum ada
            $classRooms = ClassRoom::where('school_id', $school->id)
                ->where('grade', (int) $r->grade)
                ->orderBy('id')
                ->get();

            if ($classRooms->isEmpty()) {
                $defaultClass = ClassRoom::create([
                    'school_id' => $school->id,
                    'name'      => "Kelas {$r->grade}",
                    'grade'     => (int) $r->grade,
                ]);

                $classRooms = collect([$defaultClass]);
            }

            // If there are multiple class variants (e.g. "10 IPA 1, 10 IPA 2"),
            // generate schedule only once for the grade-level. Prefer an existing
            // class named exactly "Kelas {grade}" as the representative; otherwise
            // pick the first classroom. This keeps scheduling and attendance
            // classification aligned by school + grade (not class variant).
            if ($classRooms->count() > 1 && ! $r->has('per_variant')) {
                $preferredName = "Kelas {$r->grade}";
                $representative = $classRooms->firstWhere('name', $preferredName) ?: $classRooms->first();
                $classRooms = collect([$representative]);
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

            DB::transaction(function() use ($r, $classRooms, $start, $end, &$created, &$skipped, &$errors) {
                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    foreach ($classRooms as $classRoom) {
                        try {
                            $attrs = [
                                'date' => $date->toDateString(),
                                'class_room_id' => $classRoom->id,
                                'teacher_id' => $r->teacher_id,
                            ];

                            $values = [
                                'subject_id' => $r->subject_id,
                                'start_time' => $r->start_time,
                                'end_time'   => $r->end_time,
                                'description'=> $r->description,
                            ];

                            $lesson = Lesson::firstOrCreate($attrs, $values);

                            if ($lesson->wasRecentlyCreated) {
                                $created++;
                            } else {
                                $skipped++;
                            }
                        } catch (\Illuminate\Database\QueryException $qe) {
                            // Unique constraint race — another process created it concurrently
                            $skipped++;
                        } catch (\Exception $e) {
                            $errors[] = "Error pada tanggal {$date->format('d-m-Y')} (Kelas {$classRoom->name}): {$e->getMessage()}";
                        }
                    }
                }
            });

            if (count($errors) > 0) {
                Log::warning('Lesson generation dengan error', ['errors' => $errors]);
                $errorMsg = implode(', ', array_slice($errors, 0, 3)); // Tampilkan 3 error pertama
                return back()->with('warning', "⚠️ Jadwal dibuat dengan error: $errorMsg");
            }

            $msg = "✅ Jadwal berhasil digenerate! {$created} jadwal baru dibuat untuk {$classRooms->count()} kelas grade {$r->grade} di {$school->name}, {$skipped} duplikat diabaikan. Rentang tanggal: {$start->format('d M Y')} - {$end->format('d M Y')}";
            
            Log::info('Lesson generation success', [
                'created' => $created,
                'skipped' => $skipped,
                'school_id' => $school->id,
                'school_name' => $school->name,
                'grade' => $r->grade,
                'classroom_ids' => $classRooms->pluck('id')->all(),
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
        if ($r->filled('grade'))     $q->whereHas('classRoom', fn($qq)=>$qq->where('grade',$r->grade)->whereIn('grade', [10, 11, 12]));
        if ($r->filled('date'))      $q->where('date',$r->date);

        $lessons = $q->paginate(15)->withQueryString();

        return view('lessons.teacher.list', [
            'lessons' => $lessons,
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
        abort_unless($user !== null, 403, 'Unauthorized.');

        $student = \App\Models\Student::with('classRoom.school')->where('user_id', $user->id)->firstOrFail();

        $q = Lesson::with(['teacher.user', 'subject', 'classRoom.school'])
            ->orderBy('date', 'asc');

        if ($r->filled('grade')) {
            $q->whereHas('classRoom', function($query) use ($r) {
                $query->where('grade', $r->grade);
            });
        }
        
        if ($r->filled('date')) {
            $q->whereDate('date', $r->date);
        }
        
        $lessons = $q->paginate(15)->withQueryString();
        
        return view('lessons.student.index', compact('student', 'lessons'));
    }

    // View jadwal untuk admin/guru
    public function adminView(Request $r)
    {
        $q = Lesson::with(['teacher.user', 'subject', 'classRoom'])
            ->whereHas('classRoom', fn($query) => $query->whereIn('grade', [10, 11, 12]))
            ->orderBy('date', 'desc');
        
        if ($r->filled('teacher_id')) {
            $q->where('teacher_id', $r->teacher_id);
        }
        
        if ($r->filled('date')) {
            $q->whereDate('date', $r->date);
        }
        
        $lessons = $q->paginate(20)->withQueryString();
        $teachers = Teacher::with('user')->orderBy('id')->get();
        
        return view('lessons.admin.index', compact(
            'lessons',
            'teachers'
        ));
    }

    // Edit jadwal individual
    public function editLesson(Lesson $lesson)
    {
        $subjectsList = Subject::orderBy('name')->get();
        return view('lessons.admin.edit', compact('lesson', 'subjectsList'));
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
        // Statistik jadwal total (filter only grade 10, 11, 12)
        $totalLessons = Lesson::whereHas('classRoom', fn($q) => $q->whereIn('grade', [10, 11, 12]))->count();
        $totalTeachers = Lesson::whereHas('classRoom', fn($q) => $q->whereIn('grade', [10, 11, 12]))->distinct('teacher_id')->count();
        $totalClasses = Lesson::whereHas('classRoom', fn($q) => $q->whereIn('grade', [10, 11, 12]))->distinct('class_room_id')->count();
        
        // Jadwal per status
        $lessonsWithoutTime = Lesson::whereHas('classRoom', fn($q) => $q->whereIn('grade', [10, 11, 12]))
            ->where(function($q) {
                $q->whereNull('start_time')->orWhereNull('end_time');
            })->count();
        
        // Jadwal terbaru
        $recentLessons = Lesson::with(['teacher.user', 'subject', 'classRoom'])
            ->whereHas('classRoom', fn($q) => $q->whereIn('grade', [10, 11, 12]))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Jadwal per guru
        $teachersLessonCount = Teacher::withCount(['lessons' => fn($q) => $q->whereHas('classRoom', fn($qq) => $qq->whereIn('grade', [10, 11, 12]))])
            ->orderBy('lessons_count', 'desc')
            ->get();

        return view('lessons.admin.dashboard', compact(
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
        
        // Get lessons hanya untuk guru yang login (filter grade 10, 11, 12)
        $q = Lesson::with(['subject', 'classRoom.school'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('date', 'desc');
        
        // Filter by grade if provided
        if ($r->filled('grade')) {
            $q->whereHas('classRoom', function($query) use ($r) {
                $query->where('grade', $r->grade)->whereIn('grade', [10, 11, 12]);
            });
        } else {
            $q->whereHas('classRoom', fn($query) => $query->whereIn('grade', [10, 11, 12]));
        }
        
        if ($r->filled('date')) {
            $q->whereDate('date', $r->date);
        }
        
        if ($r->filled('class_room_id')) {
            $q->where('class_room_id', $r->class_room_id);
        }
        
        $lessons = $q->paginate(20)->withQueryString();
        
        // Get classes yang diajari guru ini untuk filter (hanya grade 10, 11, 12)
        $teacherClasses = ClassRoom::whereIn('id', function($query) use ($teacher) {
            $query->select('class_room_id')
                  ->from('lessons')
                  ->where('teacher_id', $teacher->id)
                  ->distinct();
        })->whereIn('grade', [10, 11, 12])->orderBy('name')->get();
        
        return view('lessons.teacher.index', compact(
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

        // Ambil jadwal yang date < hari ini (filter grade 10, 11, 12)
        $expiredLessons = Lesson::with(['classRoom.school', 'teacher.user', 'subject'])
            ->whereHas('classRoom', fn($q) => $q->whereIn('grade', [10, 11, 12]))
            ->where('date', '<', $today->toDateString())
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('lessons.admin.logs.expired', [
            'expiredLessons' => $expiredLessons,
            'totalExpired' => Lesson::whereHas('classRoom', fn($q) => $q->whereIn('grade', [10, 11, 12]))
                ->where('date', '<', $today->toDateString())->count(),
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

        return view('lessons.admin.logs.deleted-log', [
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

