<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['lesson_id','student_id','status','marked_by','marked_at'];
    
    public function lesson(){ return $this->belongsTo(\App\Models\Lesson::class); }
    
    public function student(){ return $this->belongsTo(\App\Models\Student::class); }
    
    public function marker(){ return $this->belongsTo(\App\Models\User::class,'marked_by'); }
}

