<?php

namespace Database\Seeders;

use App\Models\VideoPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class VideoPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем VideoPost с видео по ссылке
        $videoPost = VideoPost::factory()->create([
            'title' => 'Тестовое видео 1',
            'description' => 'Описание тестового видео 1',
        ]);

        // Загружаем видео по ссылке
        try {
            $videoPost->addMediaFromUrl('https://ftp.dwish.uz/15/clip.webm')
                ->toMediaCollection('video');
        } catch (\Exception $e) {
            // Если загрузка не удалась, продолжаем без видео
            Log::warning($e->getMessage());
        }

        // Создаем остальные VideoPost без видео
        VideoPost::factory(count: 2)->create();
    }
}
