<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('auth'); // Redirect ke route login
});

Route::get('/login', [ProfileController::class, 'showAuthPage'])->name('auth');
Route::post('/login', [ProfileController::class, 'login']);
Route::post('/register', [ProfileController::class, 'register']);
Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

// web.php
Route::get('/dashboard', [ProfileController::class, 'getProfile'])->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

Route::post('/posts/upload', [PostController::class, 'upload']);
Route::get('/upload', [PostController::class, 'showUploadPage'])->name('upload');
Route::get('/posts/{postId}/detail', [PostController::class, 'getPostDetail'])->name('post.detail');



require __DIR__ . '/auth.php';
