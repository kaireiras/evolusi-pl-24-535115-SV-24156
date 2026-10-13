<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// Auth Publik (prefix /api otomatis ditambahkan oleh Laravel)
Route::post('/login', [AuthController::class, 'login']);

// Endpoint Terproteksi Token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User & Admin
    Route::get('/blog', [BlogController::class, 'apiIndex']);
    Route::post('/blog/{blogId}/comment', [CommentController::class, 'store']);
    Route::get('/komentar', [CommentController::class, 'apiIndex']);

    // Khusus Admin
    Route::middleware('admin')->group(function () {
        Route::post('/admin/blog', [BlogController::class, 'store']);
        Route::put('/admin/blog/{id}', [BlogController::class, 'update']);
        Route::delete('/admin/blog/{id}', [BlogController::class, 'destroy']);
    });
});