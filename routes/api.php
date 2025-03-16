<?php

use App\Http\Controllers\API\DeviceController;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Http\Request;
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

Route::post('/device/register', [DeviceController::class, 'register']);

Route::middleware(ApiKeyMiddleware::class)->group(function () {
    Route::get('/device/info/{deviceId}', [DeviceController::class, 'info']);
    Route::post('/leasing/update/{deviceId}', [DeviceController::class, 'update']);
});
