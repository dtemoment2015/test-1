<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'media' => $this->relationLoaded('media') 
                ? MediaResource::collection($this->media) 
                : [],
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'comments_pagination' => $this->when(
                isset($this->comments_next_cursor) || isset($this->comments_has_more),
                [
                    'next_cursor' => $this->comments_next_cursor ?? null,
                    'has_more' => $this->comments_has_more ?? false,
                ]
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
