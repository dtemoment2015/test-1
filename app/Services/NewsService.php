<?php

namespace App\Services;

use App\DTOs\NewsStoreData;
use App\DTOs\NewsUpdateData;
use App\DTOs\CommentListFilters;
use App\Models\News;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsService
{
    public function __construct(
        private NewsRepositoryInterface $repository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function getAllWithCursor(CommentListFilters $filters): array
    {
        return $this->repository->getAllWithCursor($filters->cursor, $filters->limit);
    }

    public function getById(int $id, CommentListFilters $filters): News
    {
        $news = $this->repository->findWithComments($id, $filters->cursor, $filters->limit);
        
        if (!$news) {
            throw new NotFoundHttpException('Новость не наейдена');
        }

        return $news;
    }

    public function create(NewsStoreData $data): News
    {
        return $this->repository->create([
            'title' => $data->title,
            'description' => $data->description,
        ]);
    }

    public function update(int $id, NewsUpdateData $data): News
    {
        $news = $this->repository->update($id, $data->toArray());
        
        if (!$news) {
            throw new NotFoundHttpException('Новость не найдена');
        }

        return $news;
    }

    public function delete(int $id): bool
    {
        $deleted = $this->repository->delete($id);
        
        if (!$deleted) {
            throw new NotFoundHttpException('Новость не найдена');
        }

        return true;
    }
}
