<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherTrip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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
        try {
            $r->validate([
                'date'              => 'required|date|after_or_equal:today',
                'teaching_sessions' => 'required|integer|min:0|max:3',
                'sunday_bonus'      => 'nullable|boolean',
            ]);

            // Check for duplicate
            $exists = TeacherTrip::where('teacher_id', $teacher->id)
                ->whereDate('date', $r->date)
                ->exists();
            
            if ($exists) {
                return back()->with('error', 'Trip untuk tanggal ini sudah ada');
            }

            TeacherTrip::create([
                'teacher_id'        => $teacher->id,
                'date'              => $r->date,
                'teaching_sessions' => $r->teaching_sessions,
                'sunday_bonus'      => $r->boolean('sunday_bonus'),
            ]);

            return back()->with('ok', 'Trip berhasil ditambahkan');
        } catch (\Exception $e) {
       
            return back()->with('error', 'Gagal menambahkan trip');
        }
    }

    // Update trip
    public function update(TeacherTrip $trip, Request $r)
    {
        try {
            $r->validate([
                'teaching_sessions' => 'required|integer|min:0|max:3',
                'sunday_bonus'      => 'nullable|boolean',
            ]);

            $trip->update([
                'teaching_sessions' => $r->teaching_sessions,
                'sunday_bonus'      => $r->boolean('sunday_bonus'),
            ]);

            return back()->with('ok', 'Trip berhasil diperbarui');
        } catch (\Exception $e) {
           
            return back()->with('error', 'Gagal memperbarui trip');
        }
    }

    // Delete trip
    public function destroy(TeacherTrip $trip)
    {
        try {
            $tripDate = $trip->date;
            $trip->delete();
            
         
            
            return back()->with('ok', 'Trip berhasil dihapus');
        } catch (\Exception $e) {
           
            return back()->with('error', 'Gagal menghapus trip');
        }
    }
}
