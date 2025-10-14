<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id','class_room_id','nis'];
    public function user(){ return $this->belongsTo(\App\Models\User::class); }
    public function classRoom(){ return $this->belongsTo(ClassRoom::class); }
}

