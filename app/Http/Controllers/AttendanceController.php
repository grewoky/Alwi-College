<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
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
}
