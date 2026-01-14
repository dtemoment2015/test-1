<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCursorPaginatedResource extends ResourceCollection
{
    protected $nextCursor;
    protected $hasMore;

    public function __construct($resource, $nextCursor = null, $hasMore = false)
    {
        parent::__construct($resource);
        $this->nextCursor = $nextCursor;
        $this->hasMore = $hasMore;
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => CommentResource::collection($this->collection),
            'pagination' => [
                'next_cursor' => $this->nextCursor,
                'has_more' => $this->hasMore,
            ],
        ];
    }
}
