<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        // contoh ringkasan cepat (silakan sesuaikan tabelmu)
        $stats = [
            'students' => \App\Models\Student::count(),
            'teachers' => \App\Models\Teacher::count(),
            'classes'  => \App\Models\ClassRoom::count(),
            'payments_pending' => \App\Models\Payment::where('status','pending')->count(),
        ];
        return view('dashboard.admin', compact('stats'));
    }

    public function teacher()
    {
        $teacher = \App\Models\Teacher::where('user_id', Auth::id())->first();
        $todayLessons = \App\Models\Lesson::where('teacher_id', $teacher?->id)
                          ->where('date', now()->toDateString())->count();
        $thisMonthTrips = \App\Models\TeacherTrip::where('teacher_id',$teacher?->id)
                          ->whereBetween('date',[now()->startOfMonth()->toDateString(), now()->toDateString()])
                          ->get()
                          ->sum(fn($r)=> min(3, $r->teaching_sessions + ($r->sunday_bonus?3:0)));
        return view('dashboard.teacher', compact('todayLessons','thisMonthTrips'));
    }

    public function student()
    {
        $student = \App\Models\Student::firstOrCreate(['user_id'=>Auth::id()]);
        $presentCount = \App\Models\Attendance::where('student_id',$student->id)->where('status','present')->count();
        $lastPayment = \App\Models\Payment::where('student_id',$student->id)->latest()->first();
        return view('dashboard.student', compact('presentCount','lastPayment'));
    }
}
