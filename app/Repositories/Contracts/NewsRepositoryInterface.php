<?php

namespace App\Repositories\Contracts;

use App\Models\News;

interface NewsRepositoryInterface extends RepositoryInterface
{
    public function getAllWithCursor(?string $cursor = null, int $limit = 5): array;
    public function findWithComments(int $id, ?string $cursor = null, int $limit = 5): ?News;
}
