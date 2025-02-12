<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/products', [BookController::class, 'index']);
Route::get('/products/{id}', [BookController::class, 'show']);
