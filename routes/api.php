<?php

use App\Http\Controllers\CamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/register-camera', [CamController::class, 'registerCamera']);
Route::post('/servo/move', [CamController::class, 'moveServo']);
Route::get('/api/mqtt/status', [CamController::class, 'getMqttStatus']);
Route::get('/api/camera/status/mqtt', [CamController::class, 'getCameraStatusMqtt']);
Route::get('/proxy-stream', [App\Http\Controllers\CamController::class, 'proxyStream']);
Route::get('/proxy-snapshot', [CamController::class, 'proxySnapshot']);
Route::post('/ngrok/update', [CamController::class, 'updateNgrokUrl']);
Route::post('/ngrok/remove', [CamController::class, 'removeNgrokUrl']);
Route::get('/stream-url', [CamController::class, 'getStreamUrl']);
Route::post('/camera/update-ip', [CamController::class, 'updateCameraIp']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
