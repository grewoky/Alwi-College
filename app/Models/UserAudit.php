<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAudit extends Model
{
    protected $table = 'user_audits';
    protected $fillable = [
        'action',
        'target_user_id',
        'target_student_id',
        'performed_by',
        'details',
    ];
}
