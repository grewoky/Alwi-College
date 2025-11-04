<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['date','class_room_id','subject_id','teacher_id','start_time','end_time','description'];
    public function classRoom(){ return $this->belongsTo(\App\Models\ClassRoom::class); }
    public function subject(){ return $this->belongsTo(\App\Models\Subject::class); }
    public function teacher(){ return $this->belongsTo(\App\Models\Teacher::class); }
    public function attendances(){ return $this->hasMany(\App\Models\Attendance::class); }
}

