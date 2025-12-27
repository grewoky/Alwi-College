<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletedLessonLog extends Model
{
    protected $table = 'deleted_lessons_log';

    protected $fillable = [
        'lesson_date',
        'classroom_id',
        'teacher_id',
        'subject_id',
        'start_time',
        'end_time',
        'deleted_by',
        'deletion_reason',
    ];

    protected $casts = [
        'lesson_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Scope untuk lessons yang expired (> 2 hari yang lalu)
    public static function scopeExpired($query)
    {
        return $query->where('deleted_at', '<', now()->subDays(2));
    }

    // Scope untuk lessons yang recently deleted
    public static function scopeRecent($query)
    {
        return $query->where('deleted_at', '>=', now()->subDays(2));
    }
}
