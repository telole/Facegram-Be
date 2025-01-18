<?php

use App\Http\Controllers\Auth\AuthmeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'signup']);
    Route::post('auth/login', [AuthController::class, 'Login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('posts', [PostController::class, 'store']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::delete('posts/{id}', [PostController::class, 'destroy']);
        Route::get('posts', [PostController::class, 'index']);
        Route::get('auth/me', [AuthmeController::class, 'index']);
        Route::post('users/{username}/follow', [FollowController::class, 'store']);
        Route::delete('users/{username}/unfollow', [FollowController::class, 'destroy']);
        Route::get('users/{username}/following', [FollowController::class, 'show']);
        Route::put('users/{username}/accept', [FollowController::class, 'update']);
        Route::get('users/{username}/followers', [FollowerController::class, 'index']);
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{username}', [UserController::class, 'show']);
        
    });

 });

