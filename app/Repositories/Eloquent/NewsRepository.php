<?php

namespace App\Repositories\Eloquent;

use App\Models\News;
use App\Repositories\Contracts\NewsRepositoryInterface;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function getAllWithCursor(?string $cursor = null, int $limit = 5): array
    {
        $query = $this->model->orderBy('id', 'desc');

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

    public function findWithComments(int $id, ?string $cursor = null, int $limit = 5): ?News
    {
        $news = $this->find($id);
        
        if (!$news) {
            return null;
        }

        $query = $news->comments()
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

        $news->setRelation('comments', $comments);
        $news->comments_next_cursor = $nextCursor;
        $news->comments_has_more = $hasMore;

        return $news;
    }
}
