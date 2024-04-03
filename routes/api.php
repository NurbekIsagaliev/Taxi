<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/auth/send-code', [AuthController::class, 'sendCode']);
Route::post('/auth/check-code', [AuthController::class, 'checkCode']);
Route::post('/auth/me', [AuthController::class, 'me'])->middleware('auth:api');


Route::middleware('auth:api')->group(function () {
    Route::post('/drivers', [DriverController::class, 'store']);
    Route::get('/drivers/{id}', [DriverController::class, 'show']);
    Route::put('/drivers/{id}', [DriverController::class, 'update']);
    Route::delete('/drivers/{id}', [DriverController::class, 'destroy']);
});
