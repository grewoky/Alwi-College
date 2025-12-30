<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceTracker extends Model
{
    protected $fillable = [
        'student_id',
        'attendance_count',
        'period_start_date',
        'last_attendance_date',
        'monthly_records'
    ];

    protected $casts = [
        'period_start_date' => 'datetime',
        'last_attendance_date' => 'datetime',
        'monthly_records' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if counter sudah mencapai 30 dan perlu di-reset
     */
    public function shouldReset(): bool
    {
        return $this->attendance_count >= 30;
    }

    /**
     * Reset counter dan simpan rekap bulanan
     */
    public function resetCounter()
    {
        $monthKey = now()->format('Y-m');
        $monthlyRecords = $this->monthly_records ?? [];
        $monthlyRecords[$monthKey] = $this->attendance_count;

        $this->update([
            'attendance_count' => 0,
            'period_start_date' => now(),
            'last_attendance_date' => null,
            'monthly_records' => $monthlyRecords,
        ]);
    }

    /**
     * Increment counter
     */
    public function incrementCounter()
    {
        $this->update([
            'attendance_count' => $this->attendance_count + 1,
            'last_attendance_date' => now(),
            'period_start_date' => $this->period_start_date ?? now(),
        ]);

        // Cek apakah sudah mencapai 30, jika ya auto-reset
        if ($this->shouldReset()) {
            $this->resetCounter();
        }
    }

    /**
     * Get rolling 30 days period info
     */
    public function getPeriodDaysRemaining()
    {
        if (!$this->period_start_date) {
            return 30;
        }
        
        $daysRemaining = 30 - $this->attendance_count;
        return max(0, $daysRemaining);
    }
}
