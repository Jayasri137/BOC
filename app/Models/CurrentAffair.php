<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentAffair extends Model
{
    protected $table = 'current_affairs';

    protected $fillable = [
        'author_name',
        'date',
        'title',
        'description',
        'image_path',
        'pdf_path',
    ];
    
    // optional accessor for full public URLs
public function getImageUrlAttribute()
{
    return $this->image_path ? asset('storage/' . $this->image_path) : asset('media/demoimage.png');
}

public function getPdfUrlAttribute()
{
    return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
}


}



?>