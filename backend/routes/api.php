<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/products', [BookController::class, 'index']);
Route::get('/products/{id}', [BookController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'store']);
    Route::patch('/cart/increase/{id}', [CartController::class, 'incrementQuantity']);
    Route::patch('/cart/decrease/{id}', [CartController::class, 'decrementQuantity']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
});
