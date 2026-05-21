<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->user), // relation
            'thumbnail' => $this->mediaPath, // accessor
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'move_to_blog' => $this->move_to_blog,
            'move_to_daily_news' => $this->move_to_daily_news,
            'move_to_daily_mcqs' => $this->move_to_daily_mcqs,
            'created_at' => $this->created_at?->format('d F, Y'),
            'updated_at' => $this->updated_at?->format('d F, Y'),
        ];
    }
}
