<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enum\MediaTypeEnum;
use App\Http\Requests\PdfUploadStoreRequest;
use App\Http\Requests\LiveClassUpdateRequest;
use App\Models\PdfUpload;
use Illuminate\Http\Request;

class PdfUploadRepository extends Repository
{
    /**
     * Define the model class used by this repository.
     */
    public static function model()
    {
        return PdfUpload::class;
    }

    /**
     * Store a new PDF upload from the given request.
     */
     public static function storeByRequest(PdfUploadStoreRequest $request): pdfUpload
    {
        $pdfId = $request->pdf_id ?? null;
        $pdf = null;

        if (!$pdfId || $pdfId == "null") {
            $pdf = self::create([
                'title' => $request->title,
                'serial_number' => $request->serial_number,
                'course_id' => $request->course_id,
            ]);
        } else {
            $pdf = self::query()->where('id', $pdfId)->first();
        }

        foreach ($request->contents ?? [] as $requestContent) {
            $isFree = false;
            $isForwardAble = false;

            $contentMedia = isset($requestContent['media']) ? MediaRepository::storeByRequest(
                $requestContent['media'],
                'pdf_uploaded/image',
                MediaTypeEnum::IMAGE
            ) : null;

            $isForwardAble = isset($requestContent['is_forwardable']) && $requestContent['is_forwardable'] != "0";
            $isFree = isset($requestContent['is_free']) && $requestContent['is_free'] != "0";

            $mediaLink = $requestContent['link'] ?? null;
            $media = $requestContent['media'] ?? null;

            if ($media) {
                $mediaType = self::getFileType($media);
                $mediaDuration = self::getMediaPlaytime($media);
            } elseif ($mediaLink) {
                $mediaType = MediaTypeEnum::VIDEO;
                $mediaDuration = $requestContent['duration'];
            } else {
                throw new \Exception('No media or media link provided.');
            }


            // customize media link
            $customWidth = '100%';
            $customHeight = '450';

            $mediaLink = preg_replace('/\s*title="[^"]*"/', '', $mediaLink);

            // Replace the width and height attributes in the iframe
            $customizedIframe = preg_replace(
                ['/width="\d+"/', '/height="\d+"/'], // Match width and height attributes
                ["width=\"$customWidth\"", "height=\"$customHeight\""], // Replace with custom values
                $mediaLink
            );

            $mediaLink = $customizedIframe;
//dd($requestContent);
            PdfcontentsRepository::create([
                'pdf_id' => $pdf->id,
                'media_id' => $contentMedia ? $contentMedia->id : null,
                'title' => $requestContent['title'],
                'type' => $mediaType,
                'duration' => $mediaDuration,
                'serial_number' => $requestContent['serial_number'],
                'is_forwardable' => $isForwardAble,
                'is_free' => $isFree,
                'media_link' => $mediaLink,
                'media_updated_at' => now()
            ]);
        }

       // dd($pdf);

        return $pdf;
    }


    public static function storeByRequestOld(PdfUploadStoreRequest $request)
    {
        // Example: handle optional image upload
        $media = $request->hasFile('media')
            ? MediaRepository::storeByRequest(
                $request->file('media'),
                'pdf_uploaded/image',
                MediaTypeEnum::IMAGE
            )
            : null;

        return self::create([
            'course_id'      => $request->course_id,
            'title'  => $request->title,
            'description'  => $request->description,
            'media_id' => $media,
            'deleted_at'    => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),

        ]);
    }

    /**
     * Update an existing PDF upload.
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

    
    private static function uploadFile($file)
    {
        return $file ? MediaRepository::storeByRequest(
            $file,
            'pdf_uploaded/image',
            self::getFileType($file),
        ) : null;
    }

    private static function getMediaPlaytime($file)
    {
        $mediaType = self::getFileType($file);

        $minutes = 0;

        if ($mediaType == MediaTypeEnum::AUDIO || $mediaType == MediaTypeEnum::VIDEO) {
            $track = GetId3::fromUploadedFile($file);

            $time = explode(':', $track->getPlaytime());
            $minutes = (int) $time[0] ? $time[0] : 1;
        }

        return $minutes;
    }

    private static function getFileType($file)
    {
        switch ($file->getClientMimeType()) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/jpg':
            case 'image/gif':
            case 'image/svg+xml':
                $mediaType = MediaTypeEnum::IMAGE;
                break;
            case 'video/mp4':
            case 'video/mpeg':
                $mediaType = MediaTypeEnum::VIDEO;
                break;
            case 'audio/mpeg':
            case 'audio/wav':
            case 'audio/webm':
            case 'audio/ogg':
            case 'audio/x-wav':
                $mediaType = MediaTypeEnum::AUDIO;
                break;
            case 'application/pdf':
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $mediaType = MediaTypeEnum::DOCUMENT;
                break;
            default:
                $mediaType = MediaTypeEnum::IMAGE;
                break;
        }

        return $mediaType;
    }
}
