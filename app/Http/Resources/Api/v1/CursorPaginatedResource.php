<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CursorPaginatedResource extends ResourceCollection
{
    protected $nextCursor;
    protected $hasMore;
    protected $resourceClass;

    public function __construct($resource, $resourceClass, $nextCursor = null, $hasMore = false)
    {
        parent::__construct($resource);
        $this->nextCursor = $nextCursor;
        $this->hasMore = $hasMore;
        $this->resourceClass = $resourceClass;
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->resourceClass::collection($this->collection),
            'pagination' => [
                'next_cursor' => $this->nextCursor,
                'has_more' => $this->hasMore,
            ],
        ];
    }
}
