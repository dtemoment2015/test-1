<?php

namespace App\Http\Controllers\Api\v1;

use App\DTOs\CommentListFilters;
use App\DTOs\CommentStoreData;
use App\DTOs\CommentUpdateData;
use App\Http\Resources\Api\v1\CommentCursorPaginatedResource;
use App\Http\Resources\Api\v1\CommentResource;
use App\Http\Requests\Api\v1\StoreCommentRequest;
use App\Http\Requests\Api\v1\UpdateCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function __construct(
        private CommentService $service
    ) {
    }

    public function indexForNews(Request $request, int $newsId)
    {
        $filters = CommentListFilters::fromArray($request->all());
        $result = $this->service->getCommentsForNews($newsId, $filters);
        
        return new CommentCursorPaginatedResource(
            $result['comments'],
            $result['next_cursor'],
            $result['has_more']
        );
    }

    public function storeForNews(StoreCommentRequest $request, int $newsId)
    {
        $data = CommentStoreData::fromArray($request->validated());
        $comment = $this->service->createForNews($newsId, $data);
        return new CommentResource($comment);
    }

    public function indexForVideoPost(Request $request, int $videoPostId)
    {
        $filters = CommentListFilters::fromArray($request->all());
        $result = $this->service->getCommentsForVideoPost($videoPostId, $filters);
        
        return new CommentCursorPaginatedResource(
            $result['comments'],
            $result['next_cursor'],
            $result['has_more']
        );
    }

    public function storeForVideoPost(StoreCommentRequest $request, int $videoPostId)
    {
        $data = CommentStoreData::fromArray($request->validated());
        $comment = $this->service->createForVideoPost($videoPostId, $data);
        return new CommentResource($comment);
    }

    public function storeForComment(StoreCommentRequest $request, int $commentId)
    {
        $data = CommentStoreData::fromArray($request->validated());
        $comment = $this->service->createForComment($commentId, $data);
        return new CommentResource($comment);
    }

    public function update(UpdateCommentRequest $request, int $id)
    {
        $data = CommentUpdateData::fromArray($request->validated());
        $comment = $this->service->update($id, $data);
        return new CommentResource($comment);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
