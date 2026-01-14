<?php

use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\NewsController;
use App\Http\Controllers\Api\v1\VideoPostController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // CRUD News
    Route::apiResource('news', NewsController::class);
    
    // Comments for News
    Route::get('news/{newsId}/comments', [CommentController::class, 'indexForNews']);
    Route::post('news/{newsId}/comments', [CommentController::class, 'storeForNews']);
    
    // CRUD Video Posts
    Route::apiResource('video-posts', VideoPostController::class);
    
    // Comments for Video Posts
    Route::get('video-posts/{videoPostId}/comments', [CommentController::class, 'indexForVideoPost']);
    Route::post('video-posts/{videoPostId}/comments', [CommentController::class, 'storeForVideoPost']);
    
    // Comments CRUD
    Route::post('comments/{commentId}/replies', [CommentController::class, 'storeForComment']);
    Route::put('comments/{id}', [CommentController::class, 'update']);
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);
});
