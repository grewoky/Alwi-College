<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['user_id','employee_code'];
    
    public function user()
    { 
        return $this->belongsTo(\App\Models\User::class); 
    }
    
    public function lessons()
    { 
        return $this->hasMany(\App\Models\Lesson::class, 'teacher_id'); 
    }
    
    public function trips()
    { 
        return $this->hasMany(\App\Models\TeacherTrip::class, 'teacher_id'); 
    }
}

