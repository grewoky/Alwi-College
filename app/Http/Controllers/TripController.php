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
}
