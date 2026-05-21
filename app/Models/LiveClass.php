<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'instructor',
        'start_time',
        'end_time',
        'link',
        'description',
        'media_id',
    ];
}
