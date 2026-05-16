<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatsController;

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/stats', [StatsController::class, 'index']);

// Public routes
Route::get('/collections', [CollectionController::class,'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/collections', [CollectionController::class,'store']);
    Route::put('/collections/{id}', [CollectionController::class,'update']);
    Route::delete('/collections/{id}', [CollectionController::class,'destroy']);
    Route::patch('/user', [AuthController::class, 'update']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    Route::get('/items/{collection}', [ItemController::class,'index']);
    Route::post('/items', [ItemController::class,'store']);
    Route::put('/items/{id}', [ItemController::class,'update']);
    Route::delete('/items/{id}', [ItemController::class,'destroy']);
});
