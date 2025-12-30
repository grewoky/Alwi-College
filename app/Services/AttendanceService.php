<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceTracker;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceService
{
    /**
     * Record attendance dan update tracker
     */
    public function recordAttendance(int $lessonId, int $studentId, string $status, int $markedBy)
    {
        return DB::transaction(function () use ($lessonId, $studentId, $status, $markedBy) {
            // Create or update attendance record
            $attendance = Attendance::updateOrCreate(
                [
                    'lesson_id' => $lessonId,
                    'student_id' => $studentId,
                ],
                [
                    'status' => $status,
                    'marked_by' => $markedBy,
                    'marked_at' => now(),
                ]
            );

            // Update tracker hanya jika status adalah 'present'
            if ($status === 'present') {
                $this->updateAttendanceTracker($studentId);
            }

            return $attendance;
        });
    }

    /**
     * Update attendance tracker ketika siswa hadir
     */
    public function updateAttendanceTracker(int $studentId)
    {
        $tracker = AttendanceTracker::firstOrCreate(
            ['student_id' => $studentId],
            [
                'attendance_count' => 0,
                'period_start_date' => now()->startOfMonth(),
                'monthly_records' => [],
            ]
        );

        // Check apakah perlu reset karena bulan baru (tanggal 1)
        if ($tracker->shouldResetMonthly()) {
            Log::info("Auto-reset attendance tracker for student {$studentId} - new month started");
            $tracker->resetCounter();
            // Refresh tracker after reset
            $tracker->refresh();
        }

        // Increment counter
        $tracker->incrementCounter();

        return $tracker;
    }

    /**
     * Get attendance statistics per student dalam bulan berjalan
     */
    public function getStudentMonthlyStats(int $studentId)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $stats = [
            'hadir' => 0,
            'alpha' => 0,
            'izin' => 0,
            'sakit' => 0,
            'total' => 0,
        ];

        $attendances = Attendance::where('student_id', $studentId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        foreach ($attendances as $attendance) {
            $stats[$attendance->status]++;
        }
        $stats['total'] = $attendances->count();

        return $stats;
    }

    /**
     * Export attendance data to CSV
     */
    
    /**
     * Get status label dalam bahasa Indonesia
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'present' => 'Hadir',
            'alpha' => 'Tidak Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Get attendance summary per siswa
     */
    public function getStudentAttendanceSummary(int $studentId)
    {
        $student = Student::with('attendanceTracker')->findOrFail($studentId);
        $tracker = $student->attendanceTracker;

        $monthlyStats = $this->getStudentMonthlyStats($studentId);

        return [
            'student' => $student,
            'tracker' => $tracker,
            'monthly_stats' => $monthlyStats,
            'counter_30_days' => $tracker?->attendance_count ?? 0,
            'period_start_date' => $tracker?->period_start_date ?? null,
            'days_remaining' => $tracker?->getDaysRemainingInMonth() ?? now()->daysInMonth,
            'historical_records' => $tracker?->monthly_records ?? [],
        ];
    }
}
