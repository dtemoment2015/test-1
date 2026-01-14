<?php

namespace App\Services;

use App\DTOs\VideoPostStoreData;
use App\DTOs\VideoPostUpdateData;
use App\DTOs\CommentListFilters;
use App\Models\VideoPost;
use App\Repositories\Contracts\VideoPostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VideoPostService
{
    public function __construct(
        private VideoPostRepositoryInterface $repository
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

    public function getById(int $id, CommentListFilters $filters): VideoPost
    {
        $videoPost = $this->repository->findWithComments($id, $filters->cursor, $filters->limit);
        
        if (!$videoPost) {
            throw new NotFoundHttpException('Видео-пост не найден');
        }

        return $videoPost;
    }

    public function create(VideoPostStoreData $data, ?UploadedFile $videoFile): VideoPost
    {
        $videoPost = $this->repository->create([
            'title' => $data->title,
            'description' => $data->description,
        ]);

        if ($videoFile) {
            $videoPost->addMediaFromRequest('video')
                ->toMediaCollection('video');
        }

        $videoPost->load('media');

        return $videoPost;
    }

    public function update(int $id, VideoPostUpdateData $data, ?UploadedFile $videoFile = null): VideoPost
    {
        $videoPost = $this->repository->update($id, $data->toArray());
        
        if (!$videoPost) {
            throw new NotFoundHttpException('Видео-пост не найден');
        }

        if ($videoFile) {
            $videoPost->clearMediaCollection('video');
            $videoPost->addMediaFromRequest('video')
                ->toMediaCollection('video');
        }

        $videoPost->load('media');

        return $videoPost;
    }

    public function delete(int $id): bool
    {
        $deleted = $this->repository->delete($id);
        
        if (!$deleted) {
            throw new NotFoundHttpException('Видео-пост не найден');
        }

        return true;
    }
}
