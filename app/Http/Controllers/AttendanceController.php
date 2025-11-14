<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\TeacherTrip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Form absen: list siswa kelas tsb
    public function show(Lesson $lesson)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        abort_if($lesson->teacher_id !== $teacher->id, 403, 'Unauthorized.');

        $students = Student::where('class_room_id', $lesson->class_room_id)
                    ->with('user')->orderBy('id')->get();

        // Ambil absensi eksisting utk prefill
        $existing = Attendance::where('lesson_id',$lesson->id)->pluck('status','student_id');

        return view('attendance.form', compact('lesson','students','existing'));
    }

    // Simpan absen
    public function store(Request $r, Lesson $lesson)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        abort_if($lesson->teacher_id !== $teacher->id, 403, 'Unauthorized.');

        $students = Student::where('class_room_id', $lesson->class_room_id)->pluck('id')->toArray();

        $payload = $r->input('status', []); // array: student_id => 'present'/'alpha'
        DB::transaction(function() use ($lesson, $students, $payload, $teacher) {
            foreach ($students as $sid) {
                $status = $payload[$sid] ?? 'alpha';
                Attendance::updateOrCreate(
                    ['lesson_id'=>$lesson->id, 'student_id'=>$sid],
                    ['status'=>$status, 'marked_by'=>Auth::id(), 'marked_at'=>now()]
                );
            }

            // === Trip logic: tiap lesson kasih +1 teaching_sessions hari itu, tapi cap 3 ===
            $date = new Carbon($lesson->date);
            $trip = TeacherTrip::firstOrCreate(
                ['teacher_id'=>$teacher->id, 'date'=>$date->toDateString()],
                ['teaching_sessions'=>0, 'sunday_bonus'=>false]
            );

            $trip->teaching_sessions = min(3, $trip->teaching_sessions + 1);
            // bonus Minggu (kalau hari Minggu), tandai sunday_bonus true (trip harian akan min(3, sesi + 3))
            if ($date->isSunday()) {
                $trip->sunday_bonus = true;
            }
            $trip->save();
        });

        return back()->with('ok','Absensi tersimpan & trip diperbarui.');
    }

    // Siswa: ringkasan hadir
    public function studentSummary()
    {
        $student = Student::firstOrCreate(['user_id'=>Auth::id()]);
        $today   = now()->toDateString();

        $presentCount = Attendance::where('student_id',$student->id)
            ->where('status','present')->count();

        $todayStatus = Attendance::whereHas('lesson', fn($q)=>$q->whereDate('date',$today))
            ->where('student_id',$student->id)->pluck('status')->first();

        return view('attendance.student', compact('presentCount','todayStatus'));
    }

    // Student view: list absensi mereka
    public function studentView()
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized.');

        // Get student data
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        // Get student's classroom
        $classRoom = $student->classRoom;
        
        // Get all attendance records for this student
        $attendances = Attendance::where('student_id', $student->id)
            ->with(['lesson' => ['subject', 'teacher']])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $totalSessions = $attendances->total();
        $hadir = $attendances->where('status', 'present')->count();
        $tidakHadir = $attendances->where('status', 'alpha')->count();
        $izin = $attendances->where('status', 'izin')->count();
        $sakit = $attendances->where('status', 'sakit')->count();
        
        return view('attendance.student-view', compact(
            'student',
            'classRoom',
            'attendances',
            'totalSessions',
            'hadir',
            'tidakHadir',
            'izin',
            'sakit'
        ));
    }

    // Teacher view: list classes
    public function teacherView()
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized.');

        $teacher = Teacher::where('user_id', $user->id)->firstOrFail();
        
        // Get classes taught by this teacher
        $lessons = Lesson::where('teacher_id', $teacher->id)
            ->with('classRoom')
            ->get();
        $classRooms = $lessons->pluck('classRoom')->unique('id')->filter()->values();

        // Build a list of grades the teacher teaches (10,11,12) — do not show individual class names at top-level
        $grades = $classRooms->pluck('grade')->unique()->intersect([10,11,12])->sort()->values();

        // Also prepare mapping grade => classrooms (for drill-down)
        $classesByGrade = [];
        foreach ($grades as $grade) {
            $classesByGrade[$grade] = $classRooms->filter(fn($c) => $c->grade == $grade)->values();
        }

        return view('attendance.teacher-view', compact(
            'teacher',
            'grades',
            'classesByGrade'
        ));
    }

    // Show classes for a specific grade for the current teacher (drill-down)
    public function gradeView(Request $r, $grade)
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized.');

        $teacher = Teacher::where('user_id', $user->id)->firstOrFail();

        $lessons = Lesson::where('teacher_id', $teacher->id)
            ->with('classRoom')
            ->whereHas('classRoom', fn($q) => $q->where('grade', $grade))
            ->get();

        $classRooms = $lessons->pluck('classRoom')->unique('id')->filter()->values();

        return view('attendance.teacher-grade', compact('teacher','grade','classRooms'));
    }

    // Admin view: all attendance
    public function adminView()
    {
        // Get classrooms with school and students
        $classRooms = ClassRoom::with(['school', 'students.user'])->orderBy('school_id')->orderBy('grade')->get();
        
        // Group by School -> then by Grade (Kelas 10, 11, 12)
        $groupedBySchool = $classRooms->groupBy(function($classroom) {
            return $classroom->school->name;
        });
        
        $groupedClasses = [];
        foreach ($groupedBySchool as $schoolName => $classrooms) {
            $groupedClasses[$schoolName] = $classrooms->groupBy('grade')->sortKeys();
        }
        
        // Get attendance summary
        $attendanceSummary = Attendance::selectRaw('student_id, status, COUNT(*) as count')
            ->groupBy('student_id', 'status')
            ->get();
        
        return view('attendance.admin-view', compact(
            'classRooms',
            'groupedClasses',
            'attendanceSummary'
        ));
    }

    // Get attendance data for a specific class (API endpoint)
    public function getClassAttendance($classRoomId)
    {
        $classRoom = ClassRoom::findOrFail($classRoomId);
        $students = $classRoom->students()
            ->with(['attendances' => function($query) {
                $query->whereDate('created_at', today());
            }])
            ->get();
        
        return response()->json([
            'classRoom' => $classRoom,
            'students' => $students,
        ]);
    }

    // Mark attendance for a class (show form dan process)
    public function markAttendance($classRoomId)
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized.');

        $teacher = Teacher::where('user_id', $user->id)->firstOrFail();
        
        // Get classroom and its students
        $classRoom = ClassRoom::findOrFail($classRoomId);
        $students = $classRoom->students()->with('user')->orderBy('id')->get();
        
        // Authorization: allow marking only if the teacher teaches in the same
        // school + grade combination. This prevents teachers from marking
        // attendance for unrelated schools/grades or for random class variants.
        $teachesInSameSchoolGrade = Lesson::where('teacher_id', $teacher->id)
            ->whereHas('classRoom', fn($q) => $q->where('school_id', $classRoom->school_id)->where('grade', $classRoom->grade))
            ->exists();

        if (!$teachesInSameSchoolGrade) {
            abort(403, 'Unauthorized to mark attendance for this class.');
        }

        // Get today's lesson (if exists)
        $lesson = Lesson::where('teacher_id', $teacher->id)
            ->where('class_room_id', $classRoomId)
            ->whereDate('date', today())
            ->first();
        
        return view('attendance.mark', compact(
            'classRoom',
            'students',
            'lesson'
        ));
    }

    // Store mark attendance
    public function storeMarkAttendance(Request $request, $classRoomId)
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized.');

        $teacher = Teacher::where('user_id', $user->id)->firstOrFail();
        
        // Validate
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*' => 'in:present,alpha,izin,sakit',
        ]);
        
        $classRoom = ClassRoom::findOrFail($classRoomId);
        // Authorization: allow storing only if the teacher teaches in the same
        // school + grade combination. This enforces role scoping by school+grade.
        $teachesInSameSchoolGrade = Lesson::where('teacher_id', $teacher->id)
            ->whereHas('classRoom', fn($q) => $q->where('school_id', $classRoom->school_id)->where('grade', $classRoom->grade))
            ->exists();

        if (!$teachesInSameSchoolGrade) {
            abort(403, 'Unauthorized to store attendance for this class.');
        }

        // Get or create lesson for today (safe firstOrCreate to avoid duplicates)
        $lesson = Lesson::firstOrCreate(
            [
                'date' => today(),
                'class_room_id' => $classRoomId,
                'teacher_id' => $teacher->id,
            ],
            [
                'subject_id' => null,
                'start_time' => now()->format('H:i:s'),
                'end_time' => now()->addHours(1)->format('H:i:s'),
            ]
        );
        
        // Save attendance records
        foreach ($validated['attendances'] as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'lesson_id' => $lesson->id,
                    'student_id' => $studentId,
                ],
                [
                    'status' => $status,
                    'marked_by' => $user->id,
                    'marked_at' => now(),
                ]
            );
        }
        
        return back()->with('ok', 'Absensi berhasil dicatat!');
    }
}
