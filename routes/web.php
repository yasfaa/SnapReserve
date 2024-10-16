<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('auth'); // Redirect ke route login
});

Route::get('/login', [ProfileController::class, 'showAuthPage'])->name('auth');
Route::post('/login', [ProfileController::class, 'login']);
Route::post('/register', [ProfileController::class, 'register']);

// web.php
Route::get('/dashboard', [ProfileController::class, 'getProfile'])->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');


require __DIR__ . '/auth.php';
