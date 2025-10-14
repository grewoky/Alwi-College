<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $fillable = ['school_id','name','grade'];
    public function school(){ return $this->belongsTo(School::class); }
    public function students(){ return $this->hasMany(Student::class); }
}
