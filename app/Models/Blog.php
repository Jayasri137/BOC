<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    /**
     * When the model is converted to array/json include these computed URLs
     * so your API responses will always contain 'media_path' and 'image_path'.
     */
   
    protected $fillable = [
    'user_id',
    'author_name',
    'media_id',
    'title',
    'description',
    'status',
    'move_to_blog',
    'move_to_daily_news',
    'move_to_daily_mcqs',
];

     protected $casts = [
    'status' => 'boolean',
    'move_to_blog' => 'boolean',
    'move_to_daily_news' => 'boolean',
    'move_to_daily_mcqs' => 'boolean',
];
 protected $appends = [
        'media_path',
        'image_path',
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Belongs to Media (via media_id)
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * media_path attribute - uses the related media record if available.
     * - If media->src is an absolute URL (starts with http/https) we return it.
     * - Otherwise we check Storage::exists() and return Storage::url().
     * - If not available, we fall back to image_path or placeholder.
     */
    protected function mediaPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                // if no related media return null (caller can fall back)
                if (! $this->media) {
                    return null;
                }

                $src = $this->media->src;

                if (! $src) {
                    return null;
                }

                // if absolute URL return as-is
                if (preg_match('/^https?:\\/\\//i', $src)) {
                    return $src;
                }

                // otherwise check storage (assume default disk, usually 'public')
                if (Storage::exists($src)) {
                    return Storage::url($src);
                }

                // sometimes media->src might be stored under 'public/' prefix or 'blog/' subfolder:
                // attempt common fallbacks without throwing.
                if (Storage::exists('public/' . $src)) {
                    return Storage::url('public/' . $src);
                }
                if (Storage::exists('blogs/' . $src)) {
                    return Storage::url('blogs/' . $src);
                }

                return null;
            }
        );
    }

   
    protected function imagePath(): Attribute
    {
        return Attribute::make(
            get: function () {
                // if model has a legacy `image` column and the file exists in storage/blog/
                if (! empty($this->image)) {
                    // try storage first
                    $candidate = 'blog/' . ltrim($this->image, '/');
                    if (Storage::exists($candidate)) {
                        return Storage::url($candidate);
                    }

                    // try direct public path - useful if you stored under public/storage/blog/...
                    if (file_exists(public_path('storage/blog/' . $this->image))) {
                        return asset('storage/blog/' . $this->image);
                    }

                    // if it's an absolute URL stored in `image` return it
                    if (preg_match('/^https?:\\/\\//i', $this->image)) {
                        return $this->image;
                    }

                    // final fallback: construct path assuming storage link exists
                    return asset('storage/blog/' . $this->image);
                }

                // default placeholder
            return asset('assets/images/default-blog.png');
            }
        );
    }

    /**
     * Optional convenience accessor used by older code: getImagePathAttribute
     * This keeps compatibility if somewhere you were calling $blog->imagePath
     * (camelCase) instead of $blog->image_path.
     */
    public function getImagePathAttributeLegacy()
    {
        // delegate to the new attribute accessor
        return $this->image_path;
    }

    // If you previously referenced $blog->imagePath in views/controllers and want to keep it,
    // you can add this magic getter fallback (optional):
    public function __get($key)
    {
        if ($key === 'imagePath') {
            return $this->image_path;
        }
        return parent::__get($key);
    }
}
