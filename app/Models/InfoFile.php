<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoFile extends Model
{
    protected $fillable = ['student_id','title','file_path'];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }
}
