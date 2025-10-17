<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ⬅️ penting
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Lesson;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;

class LessonController extends Controller
{
    public function showGenerate()
    {
        $classrooms = ClassRoom::with('school')->orderBy('school_id')->orderBy('grade')->get();
        $teachersList = Teacher::with('user')->orderBy('id')->get();
        $subjectsList = Subject::orderBy('name')->get();
        
        return view('lessons.generate', [
            'classrooms'  => $classrooms,
            'teachersList' => $teachersList,
            'subjectsList' => $subjectsList,
        ]);
    }

    public function generate(Request $r)
    {
        $r->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id'    => 'nullable|exists:subjects,id',
            'teacher_id'    => 'required|exists:teachers,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        $start = new Carbon($r->start_date);
        $end   = new Carbon($r->end_date);

        DB::transaction(function() use ($r, $start, $end) {
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                Lesson::firstOrCreate(
                    [
                        'date'          => $d->toDateString(),
                        'class_room_id' => $r->class_room_id,
                        'teacher_id'    => $r->teacher_id,
                    ],
                    [
                        'subject_id'    => $r->subject_id,
                        'start_time'    => null,
                        'end_time'      => null,
                    ]
                );
            }
        });

        return back()->with('ok','Jadwal berhasil digenerate setiap hari dari tanggal ' . $start->format('d M Y') . ' sampai ' . $end->format('d M Y'));
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

        return view('lessons.teacher_list', [
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
        $student = \App\Models\Student::where('user_id', $user->id)->firstOrFail();
        
        // Get lessons for student's class
        $q = Lesson::with(['teacher.user', 'subject', 'classRoom'])
            ->where('class_room_id', $student->class_room_id)
            ->orderBy('date', 'asc');
        
        if ($r->filled('date')) {
            $q->whereDate('date', $r->date);
        }
        
        $lessons = $q->paginate(15)->withQueryString();
        
        return view('lessons.student-view', compact('student', 'lessons'));
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
        $classes = ClassRoom::orderBy('name')->get();
        
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
        $r->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
        ]);

        $lesson->update([
            'subject_id' => $r->subject_id,
            'start_time' => $r->start_time,
            'end_time'   => $r->end_time,
        ]);

        return back()->with('ok', 'Jadwal berhasil diperbarui');
    }

    // Delete jadwal individual
    public function deleteLesson(Lesson $lesson)
    {
        $lesson->delete();
        return back()->with('ok', 'Jadwal berhasil dihapus');
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
}
