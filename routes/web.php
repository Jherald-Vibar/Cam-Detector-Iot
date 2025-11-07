<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CamController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('Auth.login');
});

//Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',[AuthController::class, 'login'])->name('authenticate');
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotpassForm');
Route::post('/forgot-password', [AuthController::class, 'forgotPasswordPost'])->name('resetPass');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordForm'])->name('reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/dashboard', [CamController::class, 'dashboard'])->name('user-dashboard');
    Route::get('/servo/{angle}', [CamController::class, 'moveServo']);
    Route::put('/account-update', [AuthController::class, 'updateAccount'])->name('account.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
