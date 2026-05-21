<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enum\MediaTypeEnum;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Models\Blog;

class BlogRepository extends Repository
{
    public static function model()
    {
        return Blog::class;
    }

    public static function storeByRequest(BlogStoreRequest $request)
    {
     //dd($request);
        $status = true;
        if ($request->status) {
            $status = true;
        }

        $media = $request->hasFile('media') ? MediaRepository::storeByRequest(
            $request->file('media'),
            'blog/thumbnail',
            MediaTypeEnum::IMAGE
        ) : null;

       return self::create([
    'user_id'             => auth()->id(),
    'author_name'         => $request->author_name,
    'media_id'               => $media ? $media->id : null,
    'title'               => $request->title,
    'description'         => preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $request->description),
    'status'             => $request->status ?? 0,
    'move_to_blog'       => $request->move_to_blog ?? 0,
    'move_to_daily_news' => $request->move_to_daily_news ?? 0,
    'move_to_daily_mcqs' => $request->move_to_daily_mcqs ?? 0,
]);

    }

    public static function updateByRequest(BlogUpdateRequest $request, Blog $blog)
    {
        $status = false;
        if ($request->status) {
            $status = true;
        }

        $media = $blog->image; // ✅ changed from $blog->media

        if ($request->hasFile('thumbnail')) {
            $media = MediaRepository::updateByRequest(
                $request->file('thumbnail'),
                $media,
                'blog/thumbnail',
                MediaTypeEnum::IMAGE
            );
        }

     return self::update($blog, [
    'user_id'             => auth()->id(),
    'author_name'         => $request->author_name,
    'media_id'               => is_object($media) ? $media->id : $media, // if Media model, use id; else string path
    'title'               => $request->title,
    'description'         => $request->description,
    'status'              => $status,
    'move_to_blog'        => $request->has('move_to_blog'),
    'move_to_daily_news'  => $request->has('move_to_daily_news'),
    'move_to_daily_mcqs'  => $request->has('move_to_daily_mcqs'),
]);

    }

    public function rules()
{
    return [
        'author_name' => 'required|string|max:50',
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'move_to_blog' => 'nullable|boolean',
        'move_to_daily_news' => 'nullable|boolean',
        'move_to_daily_mcqs' => 'nullable|boolean',
        'status' => 'nullable|boolean',
    ];
}
}
