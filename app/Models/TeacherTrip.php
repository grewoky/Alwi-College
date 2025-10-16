<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherTrip extends Model
{
    protected $fillable = ['teacher_id','date','teaching_sessions','sunday_bonus'];
    public function teacher(){ return $this->belongsTo(\App\Models\Teacher::class); }

    // total trip per hari = min(3, teaching_sessions + (sunday_bonus?3:0))
    public function getTripsForDayAttribute(): int
    {
        $base = $this->teaching_sessions;
        if ($this->sunday_bonus) $base += 3;
        return min(3, $base);
    }
}
