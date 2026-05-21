<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enum\MediaTypeEnum;
use App\Http\Requests\PdfcontentsStoreRequest;
use App\Http\Requests\PdfcontentsUpdateRequest;
use App\Models\Pdfcontents;

class PdfcontentsRepository extends Repository
{
    public static function model()
    {
        return Pdfcontents::class;
    }

    public static function storeByRequest(PdfcontentsStoreRequest $request)
    {
        $media = $request->hasFile('media') ? MediaRepository::storeByRequest(
            $request->file('media'),
            'course/chapter/content/media',
            MediaTypeEnum::IMAGE
        ) : null;

        return self::create([
            'pdf_id' => $request->pdf_id,
            'media_id' => $media ? $media->id : null,
            'title' => $request->title,
            'type' => $request->type,
            'serial_number' => $request->serial_number
        ]);
    }

    public static function updateByRequest(ContentUpdateRequest $request, Content $content)
    {
        if ($content->media) {
            $media = $request->hasFile('media') ? MediaRepository::updateByRequest(
                $request->file('media'),
                $content->media,
                'course/chapter/content/media',
                MediaTypeEnum::IMAGE
            ) : $content->image;
        } else {
            $media = $request->hasFile('media') ? MediaRepository::storeByRequest(
                $request->file('media'),
                'course/chapter/content/media',
                MediaTypeEnum::IMAGE
            ) : null;
        }

        return self::update($content, [
            'media_id' => $media ? $media->id : null,
            'title' => $request->title ?? $content->title,
            'type' => $request->type ?? $content->type,
            'serial_number' => $request->serial_number ?? $content->serial_number
        ]);
    }
}
