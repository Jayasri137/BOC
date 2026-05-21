<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'serial_number',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}
