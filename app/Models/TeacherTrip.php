<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherTrip extends Model
{
    protected $fillable = ['teacher_id','date','teaching_sessions','sunday_bonus'];
    public function teacher(){ return $this->belongsTo(\App\Models\Teacher::class); }

    // total trip per hari = teaching_sessions (capped 3) + bonus (3) if present
    // Business: bonus is additive and not capped into the 3-session cap.
    public function getTripsForDayAttribute(): int
    {
        $sessionPoints = min(3, max(0, (int)$this->teaching_sessions));
        $bonus = $this->sunday_bonus ? 3 : 0;
        return $sessionPoints + $bonus;
    }
}
