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
     * Check if sudah memasuki bulan baru dan perlu di-reset (reset tanggal 1 tiap bulan)
     */
    public function shouldResetMonthly(): bool
    {
        if (!$this->period_start_date) {
            return false;
        }
        
        // Reset jika period_start_date berada di bulan lain
        return $this->period_start_date->month !== now()->month || 
               $this->period_start_date->year !== now()->year;
    }

    /**
     * Reset counter dan simpan rekap bulanan
     */
    public function resetCounter()
    {
        $monthKey = $this->period_start_date?->format('Y-m') ?? now()->subMonth()->format('Y-m');
        $monthlyRecords = $this->monthly_records ?? [];
        $monthlyRecords[$monthKey] = $this->attendance_count;

        $this->update([
            'attendance_count' => 0,
            'last_attendance_date' => null,
            'period_start_date' => now()->startOfMonth(), // Reset ke tanggal 1 bulan ini
            'monthly_records' => $monthlyRecords,
        ]);
    }

    /**
     * Increment counter (no auto-reset here, reset handled in service)
     */
    public function incrementCounter()
    {
        $this->update([
            'attendance_count' => $this->attendance_count + 1,
            'last_attendance_date' => now(),
            'period_start_date' => $this->period_start_date ?? now()->startOfMonth(),
        ]);
    }

    /**
     * Get days remaining dalam bulan ini
     */
    public function getDaysRemainingInMonth()
    {
        $daysInMonth = now()->daysInMonth;
        $dayOfMonth = now()->day;
        return $daysInMonth - $dayOfMonth + 1; // +1 untuk include hari ini
    }
}
