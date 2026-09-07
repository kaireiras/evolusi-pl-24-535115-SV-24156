<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/blog');
});

// Guest Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

// Admin Routes (tanpa middleware untuk sementara)
Route::get('/admin/blog', [BlogController::class, 'adminIndex'])->name('admin.blog.index');
Route::get('/admin/blog/create', [BlogController::class, 'create'])->name('admin.blog.create');
Route::get('/admin/blog/{id}/edit', [BlogController::class, 'edit'])->name('admin.blog.edit');
Route::post('/admin/blog', [BlogController::class, 'store'])->name('admin.blog.store');
Route::put('/admin/blog/{id}', [BlogController::class, 'update'])->name('admin.blog.update');
Route::delete('/admin/blog/{id}', [BlogController::class, 'destroy'])->name('admin.blog.destroy');

// API Routes (tanpa named routes)
Route::post('/api/blog', [BlogController::class, 'store']);
Route::put('/api/blog/{id}', [BlogController::class, 'update']);
Route::delete('/api/blog/{id}', [BlogController::class, 'destroy']);
