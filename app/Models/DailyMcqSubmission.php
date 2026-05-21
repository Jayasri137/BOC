<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyMcqSubmission extends Model
{
    protected $table = 'daily_mcq_submissions';

    protected $fillable = [
        'daily_mcq_id',
        'student_name',
        'student_phone',
        'student_email',
        'answers',
        'score',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public function mcq()
    {
        return $this->belongsTo(DailyMcq::class, 'daily_mcq_id');
    }
}



?>