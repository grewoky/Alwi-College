<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'month_period',
        'amount',
        'proof_path',
        'proof_url',
        'proof_public_id',
        'status',
        'note',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }
}
