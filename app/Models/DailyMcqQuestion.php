<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DailyMcqQuestion extends Model
{
  protected $fillable = ['daily_mcq_id','type','question_text','assertion','reason','answer','order_no'];
  public function options(){ return $this->hasMany(DailyMcqQuestionOption::class); }
}


?>