<?php

namespace App\Http\Controllers\Api\v1;

use App\DTOs\CommentListFilters;
use App\DTOs\NewsStoreData;
use App\DTOs\NewsUpdateData;
use App\Http\Resources\Api\v1\CursorPaginatedResource;
use App\Http\Resources\Api\v1\NewsResource;
use App\Http\Requests\Api\v1\StoreNewsRequest;
use App\Http\Requests\Api\v1\UpdateNewsRequest;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NewsController extends Controller
{
    public function __construct(
        private NewsService $service
    ) {
    }

    public function index(Request $request)
    {
        $filters = CommentListFilters::fromArray($request->all());
        $result = $this->service->getAllWithCursor($filters);
        
        return new CursorPaginatedResource(
            $result['items'],
            NewsResource::class,
            $result['next_cursor'],
            $result['has_more']
        );
    }

    public function show(Request $request, int $id)
    {
        $filters = CommentListFilters::fromArray($request->all());
        $news = $this->service->getById($id, $filters);
        return new NewsResource($news);
    }

    public function store(StoreNewsRequest $request)
    {
        $data = NewsStoreData::fromArray($request->validated());
        $news = $this->service->create($data);
        return new NewsResource($news);
    }

    public function update(UpdateNewsRequest $request, int $id)
    {
        $data = NewsUpdateData::fromArray($request->validated());
        $news = $this->service->update($id, $data);
        return new NewsResource($news);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'News deleted successfully']);
    }
}
