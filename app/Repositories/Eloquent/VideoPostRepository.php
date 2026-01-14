<?php

namespace App\Repositories\Eloquent;

use App\Models\VideoPost;
use App\Repositories\Contracts\VideoPostRepositoryInterface;

class VideoPostRepository extends BaseRepository implements VideoPostRepositoryInterface
{
    public function __construct(VideoPost $model)
    {
        parent::__construct($model);
    }

    public function all()
    {
        return $this->model->with('media')->get();
    }

    public function getAllWithCursor(?string $cursor = null, int $limit = 5): array
    {
        $query = $this->model->with('media')->orderBy('id', 'desc');

        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $items = $query->limit($limit + 1)->get();
        
        $hasMore = $items->count() > $limit;
        if ($hasMore) {
            $items = $items->take($limit);
        }

        $nextCursor = $hasMore ? $items->last()->id : null;

        return [
            'items' => $items,
            'next_cursor' => $nextCursor,
            'has_more' => $hasMore,
        ];
    }

    public function findWithComments(int $id, ?string $cursor = null, int $limit = 5): ?VideoPost
    {
        $videoPost = $this->model->with('media')->find($id);
        
        if (!$videoPost) {
            return null;
        }

        $query = $videoPost->comments()
            ->whereNull('comment_id') // Только корневые комментарии
            ->with(['user', 'comments.user'])
            ->orderBy('id', 'desc');

        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $comments = $query->limit($limit + 1)->get();
        
        $hasMore = $comments->count() > $limit;
        if ($hasMore) {
            $comments = $comments->take($limit);
        }

        $nextCursor = $hasMore ? $comments->last()->id : null;

        $videoPost->setRelation('comments', $comments);
        $videoPost->comments_next_cursor = $nextCursor;
        $videoPost->comments_has_more = $hasMore;

        return $videoPost;
    }
}
