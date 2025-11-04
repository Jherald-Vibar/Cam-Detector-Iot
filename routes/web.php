<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Simple root route that definitely works
Route::get('/', function () {
    return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Theodore - Camera Detector</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                .btn { display: inline-block; padding: 10px 20px; margin: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <h1>🚀 Theodore App is Live!</h1>
            <p>Your Laravel app is successfully deployed on Railway.</p>
            <div>
                <a href="/login" class="btn">Login</a>
                <a href="/register" class="btn">Register</a>
                <a href="/test" class="btn">Test Route</a>
            </div>
        </body>
        </html>
    ';
});

// Auth routes - keep your original logic
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('authenticate');

// Protected routes
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/dashboard', [CamController::class, 'dashboard'])->name('user-dashboard');
    Route::get('/servo/{angle}', [CamController::class, 'moveServo']);
    Route::put('/account-update', [AuthController::class, 'updateAccount'])->name('account.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Test route to verify everything works
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel is working!',
        'app_name' => config('app.name'),
        'environment' => config('app.env'),
        'debug' => config('app.debug')
    ]);
});

// Health check route
Route::get('/health', function () {
    try {
        \DB::connection()->getPdo();
        return response()->json(['status' => 'healthy', 'database' => 'connected']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'database' => 'disconnected', 'error' => $e->getMessage()]);
    }
});
