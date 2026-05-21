<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enum\MediaTypeEnum;
use App\Http\Requests\LiveClassStoreRequest;
use App\Http\Requests\LiveClassUpdateRequest;
use App\Models\LiveClass;

class LiveClassRepository extends Repository
{
    /**
     * Define the model class used by this repository.
     */
    public static function model()
    {
        return LiveClass::class;
    }

    /**
     * Store a new live class from the given request.
     */
    public static function storeByRequest(LiveClassStoreRequest $request)
    {
        //dd($request);
        // Example: handle optional image upload
        $media = $request->hasFile('media')
            ? MediaRepository::storeByRequest(
                $request->file('media'),
                'live_classes/image',
                MediaTypeEnum::IMAGE
            )
            : null;

        return self::create([
            'title'       => $request->title,
            'instructor'  => $request->instructor,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'link'        => $request->link,
            'description' => $request->description,
            'media_id'    => $media ? $media->id : null,
        ]);
    }

    /**
     * Update an existing live class.
     */
    public static function updateByRequest(LiveClassUpdateRequest $request, LiveClass $liveClass)
    {
        // Handle image replacement
        if ($liveClass->image) {
            $media = $request->hasFile('media')
                ? MediaRepository::updateByRequest(
                    $request->file('media'),
                    $liveClass->image,
                    'live_classes/image',
                    MediaTypeEnum::IMAGE
                )
                : $liveClass->image;
        } else {
            $media = $request->hasFile('media')
                ? MediaRepository::storeByRequest(
                    $request->file('media'),
                    'live_classes/image',
                    MediaTypeEnum::IMAGE
                )
                : null;
        }

        return self::update($liveClass, [
            'title'       => $request->title ?? $liveClass->title,
            'instructor'  => $request->instructor ?? $liveClass->instructor,
            'start_time'  => $request->start_time ?? $liveClass->start_time,
            'end_time'    => $request->end_time ?? $liveClass->end_time,
            'link'        => $request->link ?? $liveClass->link,
            'description' => $request->description ?? $liveClass->description,
            'media_id'    => $media ? $media->id : null,
        ]);
    }
}
