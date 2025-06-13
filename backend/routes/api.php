<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/authenticated', [AuthController::class, 'authenticated']);

Route::controller(BookController::class)->group(function () {
    Route::get('/books', 'index');
    Route::get('/top-books', 'topBooks');
    Route::get('/books/{id}', 'show');
});

Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories/{id}', 'show');
        Route::post('/categories', 'create');
        Route::patch('/categories/{id}', 'update');
        Route::delete('/categories/{id}', 'delete');
    });

    Route::controller(BookController::class)->group(function () {
        Route::post('/books', 'create');
        Route::patch('/books/{id}', 'update');
        Route::delete('/books/{id}', 'delete');
    });

    Route::controller(CartController::class)->group(function () {
        Route::get('/cart',  'index');
        Route::post('/cart',  'store');
        Route::put('/cart/{cartDetailId}',  'update');
        Route::delete('/cart/{cartDetailId}',  'destroy');
        Route::delete('/cart', 'clear');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders',  'index');
        Route::get('/orders/{id}',  'show');
        Route::post('/orders',  'store');
        Route::post('/orders/{id}/status', 'status');
    });
});
