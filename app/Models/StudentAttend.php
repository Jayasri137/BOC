<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttend extends Model
{
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}



?>