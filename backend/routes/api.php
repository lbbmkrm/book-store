<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::controller(BookController::class)->group(function () {
    Route::get('/books', 'index');
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
        Route::get('/carts',  'index');
        Route::post('/carts',  'store');
        Route::put('/carts/{cartDetailId}',  'update');
        Route::delete('/carts/{cartDetailId}',  'destroy');
        Route::delete('/carts', 'clear');
    });

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
});
