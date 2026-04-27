<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoopRequestController;

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Public user routes
    Route::post('/requests', [CoopRequestController::class, 'store']);
    Route::get('/requests/my', [CoopRequestController::class, 'myRequests']);

    // Staff routes
    Route::get('/staff/requests', [CoopRequestController::class, 'allRequests']);
    Route::patch('/staff/requests/review', [CoopRequestController::class, 'review']);
});
