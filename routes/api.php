<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MutationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function() {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
});

Route::middleware('auth:sanctum')->group(function (){

    // user
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/{id}/history', [UserController::class, 'history']);

    // product
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{id}/history', [ProductController::class, 'history']);
    Route::post('/products/post-product', [ProductController::class, 'store']);
    Route::put('/products/update-product/{id}', [ProductController::class, 'update']);
    Route::delete('/products/delete-product/{id}', [ProductController::class, 'destroy']);

    // mutation
    Route::get('/mutations', [MutationController::class, 'index']);
    Route::post('/mutations/post-mutation', [MutationController::class, 'store']);
});
