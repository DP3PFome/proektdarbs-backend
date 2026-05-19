<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatsController;

// Health check endpoint
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Auth routes (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Stats routes (public)
Route::get('/stats', [StatsController::class, 'index']);

// Public collection routes
Route::get('/collections', [CollectionController::class, 'index']);

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // User routes
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    
    Route::patch('/user', [AuthController::class, 'update']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Collection routes
    Route::post('/collections', [CollectionController::class, 'store']);
    Route::put('/collections/{id}', [CollectionController::class, 'update']);
    Route::delete('/collections/{id}', [CollectionController::class, 'destroy']);

    // Item routes
    Route::get('/items/{collection}', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);
});

