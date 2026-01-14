<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use App\Models\VideoPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $news = News::all();
        $videoPosts = VideoPost::all();

        // Комментарии к новостям
        foreach ($news->take(10) as $newsItem) {
            // Создаем корневые комментарии
            $rootComments = Comment::factory(rand(2, 5))
                ->create([
                    'user_id' => $users->random()->id,
                    'commentable_type' => News::class,
                    'commentable_id' => $newsItem->id,
                    'comment_id' => null,
                ]);

            // Создаем ответы на комментарии
            foreach ($rootComments as $rootComment) {
                Comment::factory(rand(1, 3))
                    ->replyTo($rootComment)
                    ->create([
                        'user_id' => $users->random()->id,
                    ]);

                // Иногда создаем ответы на ответы
                if (rand(0, 1)) {
                    $reply = Comment::factory()
                        ->replyTo($rootComment)
                        ->create([
                            'user_id' => $users->random()->id,
                        ]);

                    Comment::factory(rand(0, 2))
                        ->replyTo($reply)
                        ->create([
                            'user_id' => $users->random()->id,
                        ]);
                }
            }
        }

        // Комментарии к видео постам
        foreach ($videoPosts->take(8) as $videoPost) {
            $rootComments = Comment::factory(rand(2, 4))
                ->create([
                    'user_id' => $users->random()->id,
                    'commentable_type' => VideoPost::class,
                    'commentable_id' => $videoPost->id,
                    'comment_id' => null,
                ]);

            foreach ($rootComments as $rootComment) {
                Comment::factory(rand(1, 2))
                    ->replyTo($rootComment)
                    ->create([
                        'user_id' => $users->random()->id,
                    ]);
            }
        }
    }
}
