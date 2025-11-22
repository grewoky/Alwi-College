<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherTrip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
class TripController extends Controller
{
    public function index(Request $r)
    {
        $from = $r->input('from', now()->startOfMonth()->toDateString());
        $to   = $r->input('to',   now()->toDateString());

        $teachers = Teacher::with('user')->get()->map(function($t) use ($from,$to){
            $rows = TeacherTrip::where('teacher_id',$t->id)
                ->whereBetween('date', [$from,$to])->get();

            // hitung total trips: session points (cap 3) + bonus numeric (no cap)
            $total = 0;
            foreach ($rows as $row) {
                $sessionPoints = min(3, max(0, (int)$row->teaching_sessions));
                $bonusValue = (int)($row->bonus ?? ($row->sunday_bonus ? 3 : 0));
                $total += ($sessionPoints + $bonusValue);
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
            $bonusValue = (int)($t->bonus ?? ($t->sunday_bonus ? 3 : 0));
            $totalTrips += ($sessionPoints + $bonusValue);
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
                'bonus'             => 'nullable|integer|min:0',
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
                    Log::warning('Trip date parse failed', ['raw' => $rawDate, 'err1' => $e->getMessage(), 'err2' => $e2->getMessage()]);
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

            $incomingBonus = max(0, (int)$r->input('bonus', 0));
            if (! Schema::hasColumn('teacher_trips', 'bonus')) {
                return back()->with('error', 'Kolom bonus belum tersedia. Jalankan perintah php artisan migrate untuk memperbarui struktur basis data.');
            }
            $bonusColumnExists = true;

            $existing = TeacherTrip::where('teacher_id', $teacher->id)
                ->whereDate('date', $r->date)
                ->first();

            if ($existing) {
                $existing->teaching_sessions = $r->teaching_sessions;
                if ($bonusColumnExists) {
                    $existingBonus = (int)($existing->bonus ?? 0);
                    $existing->bonus = max(0, $existingBonus + $incomingBonus);
                } else {
                    $existing->sunday_bonus = ($existing->sunday_bonus || $incomingBonus > 0);
                }
                $existing->save();

                return back()->with('ok', 'Trip untuk tanggal ini berhasil diperbarui.');
            }

            $data = [
                'teacher_id'        => $teacher->id,
                'date'              => $r->date,
                'teaching_sessions' => $r->teaching_sessions,
                'sunday_bonus'      => $r->boolean('sunday_bonus') || (!$bonusColumnExists && $incomingBonus > 0),
            ];

            $data['bonus'] = $incomingBonus;

            TeacherTrip::create($data);

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
                'bonus'             => 'nullable|integer|min:0',
                'sunday_bonus'      => 'nullable',
            ]);

            $trip->teaching_sessions = $r->teaching_sessions;
            $incomingBonus = max(0, (int)$r->input('bonus', 0));
            if (! Schema::hasColumn('teacher_trips', 'bonus')) {
                return back()->with('error', 'Kolom bonus belum tersedia. Jalankan perintah php artisan migrate untuk memperbarui struktur basis data.');
            }

            $trip->bonus = $incomingBonus;
            $trip->save();

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
