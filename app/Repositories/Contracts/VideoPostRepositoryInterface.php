<?php

namespace App\Repositories\Contracts;

use App\Models\VideoPost;

interface VideoPostRepositoryInterface extends RepositoryInterface
{
    public function getAllWithCursor(?string $cursor = null, int $limit = 5): array;
    public function findWithComments(int $id, ?string $cursor = null, int $limit = 5): ?VideoPost;
}
