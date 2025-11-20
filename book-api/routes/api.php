<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReadingListController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/user', [AuthController::class, 'user'])->name('auth.user');
    });

    // Books management (admin only)
    Route::apiResource('books', BookController::class)->except(['index', 'show']);

    // Reading list (reader only)
    Route::prefix('reading-list')->group(function () {
        Route::get('/', [ReadingListController::class, 'index'])->name('reading-list.index');
        Route::post('/{book}', [ReadingListController::class, 'store'])->name('reading-list.store');
        Route::delete('/{book}', [ReadingListController::class, 'destroy'])->name('reading-list.destroy');
    });

    // Rents management
    Route::apiResource('rents', RentController::class);

    // Users management (admin only)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/{user}/block', [UserController::class, 'block'])->name('users.block');
        Route::post('/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
    });
});
