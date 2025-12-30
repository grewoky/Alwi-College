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
                'period_start_date' => now(),
                'monthly_records' => [],
            ]
        );

        // Check apakah perlu reset berdasarkan waktu (30 hari rolling)
        if ($tracker->period_start_date && now()->diffInDays($tracker->period_start_date) >= 30) {
            $this->resetTrackerDueToTime($tracker);
        }

        // Increment counter
        $tracker->incrementCounter();

        // Auto-reset jika sudah 30 hari
        if ($tracker->shouldReset()) {
            Log::info("Auto-reset attendance tracker for student {$studentId} - reached 30 days");
        }

        return $tracker;
    }

    /**
     * Reset tracker karena sudah 30 hari dari period_start_date
     */
    public function resetTrackerDueToTime(AttendanceTracker $tracker)
    {
        Log::info("Resetting tracker for student {$tracker->student_id} due to time-based 30 day period");
        $tracker->resetCounter();
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
     * Get all attendance data untuk export CSV
     */
    public function getAttendanceDataForExport($filters = [])
    {
        $query = Attendance::with([
            'student' => fn($q) => $q->with(['user', 'classRoom' => fn($q2) => $q2->with('school'), 'attendanceTracker']),
            'lesson' => fn($q) => $q->with(['teacher' => fn($q2) => $q2->with('user'), 'classRoom']),
            'marker'
        ]);

        // Apply filters
        if (isset($filters['month']) && isset($filters['year'])) {
            $startDate = Carbon::createFromDate($filters['year'], $filters['month'], 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            // Default: current month
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        }

        if (isset($filters['school_id'])) {
            $query->whereHas('student.classRoom', fn($q) => $q->where('school_id', $filters['school_id']));
        }

        if (isset($filters['class_room_id'])) {
            $query->whereHas('student', fn($q) => $q->where('class_room_id', $filters['class_room_id']));
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Export attendance data to CSV
     */
    public function exportToCSV($attendances = null, $filename = null)
    {
        if (!$attendances) {
            $attendances = $this->getAttendanceDataForExport();
        }

        if (!$filename) {
            $filename = 'attendance_' . now()->format('Y-m-d_His') . '.csv';
        }

        $headers = [
            'Tanggal',
            'Nama Siswa',
            'NIS',
            'Kelas',
            'Sekolah',
            'Status Absensi',
            'Guru Penginput',
            'Mata Pelajaran',
            'Counter 30 Hari',
            'Tanggal Mulai Period',
        ];

        $rows = $attendances->map(function ($attendance) {
            return [
                $attendance->created_at->format('d-m-Y H:i:s'),
                optional($attendance->student)->user->name ?? '-',
                optional($attendance->student)->nis ?? '-',
                optional(optional($attendance->student)->classRoom)->name ?? '-',
                optional(optional(optional($attendance->student)->classRoom)->school)->name ?? '-',
                $this->getStatusLabel($attendance->status),
                optional($attendance->marker)->name ?? '-',
                optional($attendance->lesson)->subject->name ?? '-',
                optional(optional($attendance->student)->attendanceTracker)->attendance_count ?? 0,
                optional(optional($attendance->student)->attendanceTracker)->period_start_date?->format('d-m-Y') ?? '-',
            ];
        })->toArray();

        return $this->generateCSVContent($headers, $rows, $filename);
    }

    /**
     * Generate CSV content
     */
    private function generateCSVContent($headers, $rows, $filename)
    {
        $output = fopen('php://output', 'w');
        
        // Set UTF-8 BOM for Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Write headers
        fputcsv($output, $headers, ';');

        // Write rows
        foreach ($rows as $row) {
            fputcsv($output, $row, ';');
        }

        fclose($output);

        return [
            'filename' => $filename,
            'content_type' => 'text/csv; charset=utf-8',
        ];
    }

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
            'days_remaining' => $tracker?->getPeriodDaysRemaining() ?? 30,
            'historical_records' => $tracker?->monthly_records ?? [],
        ];
    }
}
