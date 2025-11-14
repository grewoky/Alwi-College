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

            // hitung total trips: sessions (capped 3) + sunday bonus (3) per entry
            $total = 0;
            foreach ($rows as $row) {
                $sessionPoints = min(3, max(0, (int)$row->teaching_sessions));
                $bonus = $row->sunday_bonus ? 3 : 0;
                $total += ($sessionPoints + $bonus);
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

        $totalTrips = 0;
        foreach ($trips as $t) {
            $sessionPoints = min(3, max(0, (int)$t->teaching_sessions));
            $bonus = $t->sunday_bonus ? 3 : 0;
            $totalTrips += ($sessionPoints + $bonus);
        }

        return view('trips.show', compact('teacher', 'trips', 'totalTrips', 'from', 'to'));
    }

    // Store new trip (manual entry)
    public function store(Teacher $teacher, Request $r)
    {
        try {
            $r->validate([
                'date'              => 'required|date',
                'teaching_sessions' => 'required|integer|min:0|max:3',
                // checkbox input from HTML may send 'on' which fails strict boolean validation;
                // accept nullable here and coerce via has() when saving.
                'sunday_bonus'      => 'nullable',
            ]);

            // Parse date robustly: try ISO (Y-m-d) first, then common UI format d/m/Y
            $rawDate = $r->input('date');
            try {
                $inputDate = Carbon::createFromFormat('Y-m-d', $rawDate) ?: Carbon::parse($rawDate);
            } catch (\Exception $e) {
                try {
                    $inputDate = Carbon::createFromFormat('d/m/Y', $rawDate);
                } catch (\Exception $e2) {
                    \Log::warning('Trip date parse failed', ['raw' => $rawDate, 'err1' => $e->getMessage(), 'err2' => $e2->getMessage()]);
                    return back()->with('error', 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.');
                }
            }
            $inputDate = $inputDate->startOfDay();
            $today = Carbon::today();

            // Only allow adding trip for the current day
            if ($inputDate->lt($today)) {
                return back()->with('error', 'Tidak dapat menambahkan trip untuk tanggal sebelumnya.');
            }
            if ($inputDate->gt($today)) {
                return back()->with('error', 'Tidak dapat menambahkan trip untuk tanggal yang belum datang. Trip hanya dapat ditambahkan untuk hari ini.');
            }

            // Bonus is allowed for any day. (Business decided bonus is not limited to Sundays.)

            // If a trip for this teacher+date already exists, update it so bonus can be added
            $existing = TeacherTrip::where('teacher_id', $teacher->id)
                ->whereDate('date', $r->date)
                ->first();

            if ($existing) {
                $existing->update([
                    'teaching_sessions' => $r->teaching_sessions,
                    'sunday_bonus'      => $r->has('sunday_bonus'),
                ]);

                return back()->with('ok', 'Trip untuk tanggal ini berhasil diperbarui.');
            }

            TeacherTrip::create([
                'teacher_id'        => $teacher->id,
                'date'              => $r->date,
                'teaching_sessions' => $r->teaching_sessions,
                'sunday_bonus'      => $r->has('sunday_bonus'),
            ]);

            return back()->with('ok', 'Trip berhasil ditambahkan');
        } catch (\Exception $e) {
            // log exception for debugging and return a helpful error message
            Log::error('Failed to store teacher trip', ['error' => $e->getMessage(), 'input' => $r->all()]);
            return back()->with('error', 'Gagal menambahkan trip: ' . $e->getMessage());
        }
    }

    // Update trip
    public function update(TeacherTrip $trip, Request $r)
    {
        try {
            $r->validate([
                'teaching_sessions' => 'required|integer|min:0|max:3',
                'sunday_bonus'      => 'nullable',
            ]);

            $trip->update([
                'teaching_sessions' => $r->teaching_sessions,
                'sunday_bonus'      => $r->has('sunday_bonus'),
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
