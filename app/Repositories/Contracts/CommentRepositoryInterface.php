<?php

namespace App\Repositories\Contracts;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface extends RepositoryInterface
{
    public function getCommentsForCommentable(string $commentableType, int $commentableId, ?string $cursor = null, int $limit = 15): array;
    public function createForCommentable(string $commentableType, int $commentableId, array $data): Comment;
}
