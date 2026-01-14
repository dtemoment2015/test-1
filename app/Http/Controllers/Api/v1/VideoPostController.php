<?php

namespace App\Http\Controllers\Api\v1;

use App\DTOs\CommentListFilters;
use App\DTOs\VideoPostStoreData;
use App\DTOs\VideoPostUpdateData;
use App\Http\Resources\Api\v1\CursorPaginatedResource;
use App\Http\Resources\Api\v1\VideoPostResource;
use App\Http\Requests\Api\v1\StoreVideoPostRequest;
use App\Http\Requests\Api\v1\UpdateVideoPostRequest;
use App\Services\VideoPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VideoPostController extends Controller
{
    public function __construct(
        private VideoPostService $service
    ) {
    }

    public function index(Request $request)
    {
        $filters = CommentListFilters::fromArray($request->all());
        $result = $this->service->getAllWithCursor($filters);
        
        return new CursorPaginatedResource(
            $result['items'],
            VideoPostResource::class,
            $result['next_cursor'],
            $result['has_more']
        );
    }

    public function show(Request $request, int $id)
    {
        $filters = CommentListFilters::fromArray($request->all());
        $videoPost = $this->service->getById($id, $filters);
        return new VideoPostResource($videoPost);
    }

    public function store(StoreVideoPostRequest $request)
    {
        $data = VideoPostStoreData::fromArray($request->validated());
        $videoFile = $request->file('video');
        $videoPost = $this->service->create($data, $videoFile);
        return new VideoPostResource($videoPost);
    }

    public function update(UpdateVideoPostRequest $request, int $id)
    {
        $data = VideoPostUpdateData::fromArray($request->validated());
        $videoFile = $request->file('video');
        $videoPost = $this->service->update($id, $data, $videoFile);
        return new VideoPostResource($videoPost);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Video post deleted successfully']);
    }
}
