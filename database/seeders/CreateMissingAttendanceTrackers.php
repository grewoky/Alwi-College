<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceTracker;
use App\Models\Student;

class CreateMissingAttendanceTrackers extends Seeder
{
    /**
     * Run the seeder untuk membuat tracker untuk attendance records yang sudah ada
     * Monthly reset: period_start_date di-set ke tanggal 1 bulan berjalan
     */
    public function run(): void
    {
        // Ambil semua attendance records yang statusnya present
        $attendances = Attendance::where('status', 'present')->get();
        
        foreach ($attendances as $attendance) {
            $student = $attendance->student;
            
            if (!$student) continue;
            
            // Cek apakah tracker sudah ada
            $tracker = AttendanceTracker::where('student_id', $student->id)->first();
            
            if (!$tracker) {
                // Buat tracker baru dengan period_start_date = tanggal 1 bulan berjalan
                AttendanceTracker::create([
                    'student_id' => $student->id,
                    'attendance_count' => 1,
                    'period_start_date' => now()->startOfMonth(),
                    'last_attendance_date' => $attendance->created_at,
                    'monthly_records' => [],
                ]);
                
                echo "✓ Created tracker for student {$student->id}\n";
            } else {
                // Update counter jika belum dihitung
                if ($tracker->attendance_count == 0) {
                    $tracker->update([
                        'attendance_count' => 1,
                        'period_start_date' => now()->startOfMonth(),
                        'last_attendance_date' => $attendance->created_at,
                    ]);
                    
                    echo "✓ Updated tracker for student {$student->id}\n";
                }

            }
        }
        
        echo "\nDone! All missing attendance trackers created.\n";
    }
}
