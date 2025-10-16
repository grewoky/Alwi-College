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
        return view('lessons.generate', [
            'classes'  => ClassRoom::with('school')->orderBy('school_id')->orderBy('grade')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::with('user')->orderBy('id')->get(),
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
            for ($d = $start->copy(); $d->lte($end); $d->addDays(2)) {
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

        return back()->with('ok','Jadwal berhasil digenerate per 2 hari.');
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
}
