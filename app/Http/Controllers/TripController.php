<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherTrip;
use Carbon\Carbon;

class TripController extends Controller
{
    public function index(Request $r)
    {
        $from = $r->input('from', now()->startOfMonth()->toDateString());
        $to   = $r->input('to',   now()->toDateString());

        $teachers = Teacher::with('user')->get()->map(function($t) use ($from,$to){
            $rows = TeacherTrip::where('teacher_id',$t->id)
                ->whereBetween('date', [$from,$to])->get();

            // hitung total trips
            $total = 0;
            foreach ($rows as $row) {
                $total += min(3, $row->teaching_sessions + ($row->sunday_bonus ? 3 : 0));
                if ($total < 0) $total = 0;
            }

            return [
                'id'    => $t->id,
                'name'  => $t->user->name ?? ("Teacher #".$t->id),
                'total' => $total,
            ];
        });

        return view('trips.index', compact('teachers','from','to'));
    }

    // Show teacher trip details
    public function show(Teacher $teacher, Request $r)
    {
        $from = $r->input('from', now()->startOfMonth()->toDateString());
        $to   = $r->input('to',   now()->toDateString());

        $trips = TeacherTrip::where('teacher_id', $teacher->id)
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'desc')
            ->get();

        $totalTrips = $trips->sum(fn($t) => min(3, $t->teaching_sessions + ($t->sunday_bonus ? 3 : 0)));

        return view('trips.show', compact('teacher', 'trips', 'totalTrips', 'from', 'to'));
    }

    // Store new trip (manual entry)
    public function store(Teacher $teacher, Request $r)
    {
        $r->validate([
            'date'              => 'required|date',
            'teaching_sessions' => 'required|integer|min:0|max:3',
            'sunday_bonus'      => 'nullable|boolean',
        ]);

        TeacherTrip::create([
            'teacher_id'        => $teacher->id,
            'date'              => $r->date,
            'teaching_sessions' => $r->teaching_sessions,
            'sunday_bonus'      => $r->boolean('sunday_bonus'),
        ]);

        return back()->with('ok', 'Trip berhasil ditambahkan');
    }

    // Update trip
    public function update(TeacherTrip $trip, Request $r)
    {
        $r->validate([
            'teaching_sessions' => 'required|integer|min:0|max:3',
            'sunday_bonus'      => 'nullable|boolean',
        ]);

        $trip->update([
            'teaching_sessions' => $r->teaching_sessions,
            'sunday_bonus'      => $r->boolean('sunday_bonus'),
        ]);

        return back()->with('ok', 'Trip berhasil diperbarui');
    }

    // Delete trip
    public function destroy(TeacherTrip $trip)
    {
        $trip->delete();
        return back()->with('ok', 'Trip berhasil dihapus');
    }
}
