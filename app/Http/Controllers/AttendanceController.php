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
use Illuminate\Support\Facades\Log;
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
                ['teaching_sessions'=>0, 'sunday_bonus'=>false, 'bonus' => 0]
            );

            $trip->teaching_sessions = min(3, $trip->teaching_sessions + 1);
            // If DB has numeric 'bonus' column, add 3 points for Sunday; otherwise keep legacy sunday_bonus boolean
            if ($date->isSunday()) {
                if (array_key_exists('bonus', $trip->getAttributes())) {
                    $trip->bonus = max(0, (int)($trip->bonus ?? 0) + 3);
                } else {
                    $trip->sunday_bonus = true;
                }
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

    // Teacher view: list attendance summary (like student view)
    public function teacherView()
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized.');

        $teacher = Teacher::where('user_id', $user->id)->firstOrFail();
        
        // Get all attendance records where this teacher marked the attendance
        $attendances = Attendance::whereHas('lesson', fn($q) => $q->where('teacher_id', $teacher->id))
            ->with(['lesson' => ['classRoom', 'subject'], 'student.user', 'student.classRoom'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $totalSessions = $attendances->total();
        $hadir = $attendances->where('status', 'present')->count();
        $tidakHadir = $attendances->where('status', 'alpha')->count();
        $izin = $attendances->where('status', 'izin')->count();
        $sakit = $attendances->where('status', 'sakit')->count();

        return view('attendance.teacher-view', compact(
            'teacher',
            'attendances',
            'totalSessions',
            'hadir',
            'tidakHadir',
            'izin',
            'sakit'
        ));
    }

    // Show classes for marking attendance (all grades 10, 11, 12) grouped by school
    public function markAttendanceSelect()
    {
        $user = Auth::user();
        abort_unless($user !== null, 403, 'Unauthorized.');

        $teacher = Teacher::where('user_id', $user->id)->firstOrFail();

        // Get all unique classrooms taught by this teacher with school info
        // Filter: only lessons dari 2 hari terakhir (exclude lessons yang sudah lewat > 2 hari)
        $twoHaysAgoDate = now()->subDays(2)->format('Y-m-d');
        
        $lessons = Lesson::where('teacher_id', $teacher->id)
            ->where('date', '>=', $twoHaysAgoDate)
            ->with(['classRoom.school'])
            ->get();
        $classRooms = $lessons->pluck('classRoom')->unique('id')->filter();

        // Ensure only grades 10, 11, 12 are shown
        $classRooms = $classRooms->filter(fn($c) => in_array($c->grade, [10, 11, 12]));

        // Group by School -> Grade
        $classesBySchoolAndGrade = [];
        foreach ($classRooms as $classRoom) {
            $schoolName = $classRoom->school->name ?? 'Sekolah Tidak Diketahui';
            $grade = $classRoom->grade;
            
            if (!isset($classesBySchoolAndGrade[$schoolName])) {
                $classesBySchoolAndGrade[$schoolName] = [];
            }
            if (!isset($classesBySchoolAndGrade[$schoolName][$grade])) {
                $classesBySchoolAndGrade[$schoolName][$grade] = [];
            }
            $classesBySchoolAndGrade[$schoolName][$grade][] = $classRoom;
        }
        
        // Sort schools by name (Bangau, IGS, Kumbang, Negeri, Xavega)
        ksort($classesBySchoolAndGrade);

        return view('attendance.teacher-select-class', compact('teacher', 'classesBySchoolAndGrade'));
    }

    // Select which classroom variant for a specific school and grade
    public function selectClassroomVariant($schoolName, $grade)
    {
        try {
            $user = Auth::user();
            abort_unless($user !== null, 403, 'Unauthorized.');

            $teacher = Teacher::where('user_id', $user->id)->firstOrFail();

            // Get classrooms for this school and grade taught by this teacher using pure query builder
            // NOTE: Removed date filter temporarily to test functionality
            $classRooms = ClassRoom::with('school')
                ->whereHas('school', function($q) use ($schoolName) {
                    $q->where('name', $schoolName);
                })
                ->where('grade', (int)$grade)
                ->whereHas('lessons', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                ->orderBy('name')
                ->get();

            Log::info("selectClassroomVariant: school=$schoolName, grade=$grade, count=" . $classRooms->count());

            if ($classRooms->isEmpty()) {
                Log::warning("No classrooms found for school=$schoolName, grade=$grade, teacher_id=$teacher->id");
                return redirect()
                    ->route('attendance.mark.select')
                    ->with('error', 'Tidak ada kelas tersedia untuk pilihan tersebut. Pastikan jadwal guru masih aktif.');
            }

            return view('attendance.select-classroom-variant', compact('teacher', 'schoolName', 'grade', 'classRooms'));
        } catch (\Exception $e) {
            Log::error('selectClassroomVariant error: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat memuat kelas: ' . $e->getMessage());
        }
    }

    // Show grades for a specific teacher (drill-down - kept for compatibility)
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

    // Admin view: all attendance data (current month only, read-only)
    public function adminView(Request $request)
    {
        try {
            // Get start and end of current month
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            
            // Get attendance data for current month only with proper relationships
            $attendances = Attendance::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->with([
                    'student' => fn($q) => $q->with(['user', 'classRoom' => fn($q2) => $q2->with('school')]),
                    'lesson' => fn($q) => $q->with(['teacher' => fn($q2) => $q2->with('user')])
                ])
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info("AdminView: Total attendance records: " . $attendances->count());
            
            // Get paginated version for pagination links
            $attendancesPaginated = Attendance::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->with([
                    'student' => fn($q) => $q->with(['user', 'classRoom' => fn($q2) => $q2->with('school')]),
                    'lesson' => fn($q) => $q->with(['teacher' => fn($q2) => $q2->with('user')])
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(50);
            
            // Calculate monthly statistics
            $stats = [
                'totalRecords' => $attendances->count(),
                'hadir' => $attendances->where('status', 'present')->count(),
                'tidakHadir' => $attendances->where('status', 'alpha')->count(),
                'izin' => $attendances->where('status', 'izin')->count(),
                'sakit' => $attendances->where('status', 'sakit')->count(),
            ];
            
            $currentMonth = $startOfMonth->format('F Y');
            
            Log::info("AdminView Stats: " . json_encode($stats));
            
            return view('attendance.admin-view', compact(
                'attendances',
                'attendancesPaginated',
                'stats',
                'currentMonth',
                'startOfMonth',
                'endOfMonth'
            ));
        } catch (\Exception $e) {
            Log::error('AdminView error: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat memuat data absensi: ' . $e->getMessage());
        }
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

    // Mark attendance for a class (show form and process)
    public function markAttendance($classRoomId)
    {
        try {
            $user = Auth::user();
            abort_unless($user !== null, 403, 'Unauthorized.');

            $teacher = Teacher::where('user_id', $user->id)->firstOrFail();
            
            // Get classroom and its students
            $classRoom = ClassRoom::with('school')->findOrFail($classRoomId);
            $students = $classRoom->students()->with('user')->orderBy('id')->get();
            
            Log::info("markAttendance: classRoomId=$classRoomId, teacher_id=$teacher->id, students count=" . $students->count());
            
            // Authorization: allow marking only if the teacher teaches in the same
            // school + grade combination.
            $teachesInSameSchoolGrade = Lesson::where('teacher_id', $teacher->id)
                ->whereHas('classRoom', fn($q) => $q->where('school_id', $classRoom->school_id)->where('grade', $classRoom->grade))
                ->exists();

            Log::info("markAttendance: teachesInSameSchoolGrade=$teachesInSameSchoolGrade for school_id=" . $classRoom->school_id . ", grade=" . $classRoom->grade);

            if (!$teachesInSameSchoolGrade) {
                Log::warning("markAttendance: Unauthorized - teacher doesn't teach in this school/grade combination");
                return redirect()
                    ->route('attendance.select.classroom', [
                        'school' => $classRoom->school->name,
                        'grade' => $classRoom->grade,
                    ])
                    ->with('error', 'Anda tidak memiliki jadwal aktif untuk kombinasi sekolah dan kelas ini.');
            }

            // Check if attendance already marked for this class TODAY
            $todayLesson = Lesson::where('teacher_id', $teacher->id)
                ->where('class_room_id', $classRoomId)
                ->whereDate('date', today())
                ->first();
            
            if ($todayLesson) {
                Log::info("markAttendance: Already marked today for this class");
                return redirect()
                    ->route('attendance.select.classroom', [
                        'school' => $classRoom->school->name,
                        'grade' => $classRoom->grade,
                    ])
                    ->with('warning', 'Absensi untuk kelas ini sudah dicatat hari ini. Anda hanya dapat menginput satu kali per hari.');
            }

            $lesson = null;
            
            Log::info("markAttendance: Returning form view for classRoom=" . $classRoom->name);
            return view('attendance.mark', compact(
                'classRoom',
                'students',
                'lesson'
            ));
        } catch (\Exception $e) {
            Log::error('markAttendance error: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return redirect()
                ->route('attendance.mark.select')
                ->with('error', 'Terjadi kesalahan saat membuka form absensi: ' . $e->getMessage());
        }
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
