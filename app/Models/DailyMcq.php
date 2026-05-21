<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DailyMcq extends Model
{
protected $fillable = ['date','title','duration','mark_per_question','pass_marks','instructions','pdf_file','is_published'];
public function questions(){ return $this->hasMany(DailyMcqQuestion::class); }
}
