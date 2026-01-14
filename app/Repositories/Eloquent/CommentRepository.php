<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function getCommentsForCommentable(string $commentableType, int $commentableId, ?string $cursor = null, int $limit = 15): array
    {
        $query = $this->model->where('commentable_type', $commentableType)
            ->where('commentable_id', $commentableId)
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

        return [
            'comments' => $comments,
            'next_cursor' => $nextCursor,
            'has_more' => $hasMore,
        ];
    }

    public function createForCommentable(string $commentableType, int $commentableId, array $data): Comment
    {
        $data['commentable_type'] = $commentableType;
        $data['commentable_id'] = $commentableId;
        
        return $this->create($data);
    }
}
