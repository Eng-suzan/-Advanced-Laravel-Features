<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\API\CategoryController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('posts', PostController::class)
        ->names('api.posts');

    Route::apiResource('categories', CategoryController::class)
        ->names('api.categories');
});
Route::post('/comments', [CommentController::class, 'store'])
    ->name('comments.store');