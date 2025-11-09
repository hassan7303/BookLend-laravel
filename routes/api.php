<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\FineController;

Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index']);
        Route::get('{id}', [BookController::class, 'show']);
        Route::post('/', [BookController::class, 'store']);
        Route::put('{id}', [BookController::class, 'update']);
        Route::delete('{id}', [BookController::class, 'destroy']);
    });

    Route::prefix('copies')->group(function () {
        Route::get('/', [BookCopyController::class, 'index']);
        Route::get('{id}', [BookCopyController::class, 'show']);
        Route::post('/', [BookCopyController::class, 'store']);
        Route::put('{id}', [BookCopyController::class, 'update']);
        Route::post('{id}/library-only', [BookCopyController::class, 'setLibraryOnly']);
    });

    Route::prefix('borrows')->group(function () {
        Route::post('/', [BorrowController::class, 'borrow']);
        Route::post('return/{borrowId}', [BorrowController::class, 'return']);
        Route::get('my', [BorrowController::class, 'myBorrows']);
    });

    Route::prefix('fines')->group(function () {
        Route::get('/', [FineController::class, 'index']);
        Route::post('{id}/pay', [FineController::class, 'pay']);
    });
});
