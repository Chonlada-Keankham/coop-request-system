<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoopRequestController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/requests', [CoopRequestController::class, 'store']);
    Route::get('/requests/my', [CoopRequestController::class, 'myRequests']);

    Route::get('/staff/requests', [CoopRequestController::class, 'allRequests']);
    Route::patch('/staff/requests/review', [CoopRequestController::class, 'review']);
});
