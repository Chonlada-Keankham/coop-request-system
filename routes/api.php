<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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

// Temporary route for Render free plan migration/seed debugging.
// Use once, then remove this route after migration and seeding are completed.
Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $migrate = Artisan::output();

        Artisan::call('db:seed', ['--force' => true]);
        $seed = Artisan::output();

        return response()->json([
            'success' => true,
            'message' => 'Migration and seeding completed',
            'migrate' => $migrate,
            'seed' => $seed,
        ], 200);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
});
