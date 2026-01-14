<?php

namespace App\Services;

use App\DTOs\CommentStoreData;
use App\DTOs\CommentUpdateData;
use App\DTOs\CommentListFilters;
use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use App\Models\VideoPost;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentService
{
    public function __construct(
        private CommentRepositoryInterface $repository,
        private UserRepositoryInterface $userRepository
    ) {
    }

    //запрос на тестового пользователя - рандом, как будто беру Auth::user()
    private function getTestUser(): User
    {
        $user = $this->userRepository->getRandom();
        
        if (!$user) {
            throw new NotFoundHttpException('Пользователине найдены в базе данных');
        }
        
        return $user;
    }

    public function getCommentsForNews(int $newsId, CommentListFilters $filters): array
    {
        return $this->repository->getCommentsForCommentable(
            News::class,
            $newsId,
            $filters->cursor,
            $filters->limit
        );
    }

    public function getCommentsForVideoPost(int $videoPostId, CommentListFilters $filters): array
    {
        return $this->repository->getCommentsForCommentable(
            VideoPost::class,
            $videoPostId,
            $filters->cursor,
            $filters->limit
        );
    }

    public function createForNews(int $newsId, CommentStoreData $data): Comment
    {
        $user = $this->getTestUser();
        
        $comment = $this->repository->createForCommentable(News::class, $newsId, [
            'user_id' => $user->id,
            'text' => $data->text,
            'comment_id' => $data->commentId,
        ]);

        $comment->load(['user', 'comments.user']);

        return $comment;
    }

    public function createForVideoPost(int $videoPostId, CommentStoreData $data): Comment
    {
        $user = $this->getTestUser();
        
        $comment = $this->repository->createForCommentable(VideoPost::class, $videoPostId, [
            'user_id' => $user->id,
            'text' => $data->text,
            'comment_id' => $data->commentId,
        ]);

        $comment->load(['user', 'comments.user']);

        return $comment;
    }

    public function createForComment(int $commentId, CommentStoreData $data): Comment
    {
        $parentComment = $this->repository->find($commentId);
        
        if (!$parentComment) {
            throw new NotFoundHttpException('Комментарий не найден');
        }

        $user = $this->getTestUser();

        $comment = $this->repository->createForCommentable(
            $parentComment->commentable_type,
            $parentComment->commentable_id,
            [
                'user_id' => $user->id,
                'text' => $data->text,
                'comment_id' => $commentId,
            ]
        );

        $comment->load(['user', 'comments.user']);

        return $comment;
    }

    public function update(int $id, CommentUpdateData $data): Comment
    {
        $comment = $this->repository->update($id, $data->toArray());
        
        if (!$comment) {
            throw new NotFoundHttpException('Комментарий не найден');
        }

        $comment->load(['user', 'comments.user']);

        return $comment;
    }

    public function delete(int $id): bool
    {
        $deleted = $this->repository->delete($id);
        
        if (!$deleted) {
            throw new NotFoundHttpException('Комментарий не найден');
        }

        return true;
    }
}
