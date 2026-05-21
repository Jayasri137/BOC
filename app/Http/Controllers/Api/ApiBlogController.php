<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class ApiBlogController extends Controller
{
    public function index(Request $request)
    {
        // eager load relations you need (media, user) to avoid N+1
        $blogs = Blog::with(['media', 'user'])->latest()->get();

        $payload = $blogs->map(function ($blog) {
            return [
                'id'          => $blog->id,
                'title'       => $blog->title,
                'slug'        => $blog->slug, // uses Blog::getSlugAttribute()
                'description' => $blog->excerpt, // uses Blog::getExcerptAttribute()
                'content'     => $blog->content, // Blog::getContentAttribute() or column
                'image'       => $blog->media_path, // Blog::getMediaPathAttribute()
                'media_id'    => $blog->media_id ?? null,
                'author_name' => $blog->author_name ?? ($blog->user->name ?? 'Unknown'),
                'status'      => (bool) $blog->status,
                'created_at'  => optional($blog->created_at)->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $payload,
        ], 200);
    }

    public function show($id)
    {
        $blog = Blog::with(['media', 'user'])->find($id);

        if (! $blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'          => $blog->id,
                'title'       => $blog->title,
                'slug'        => $blog->slug,
                'description' => $blog->excerpt,
                'content'     => $blog->content,
                'image'       => $blog->media_path,
                'media_id'    => $blog->media_id ?? null,
                'author_name' => $blog->author_name ?? ($blog->user->name ?? 'Unknown'),
                'status'      => (bool) $blog->status,
                'created_at'  => optional($blog->created_at)->toDateTimeString(),
            ]
        ], 200);
    }
    
       
    
    
    
}
