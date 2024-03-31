<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/send-code', [AuthController::class, 'sendCode']);
Route::post('/auth/check-code', [AuthController::class, 'checkCode']);
Route::post('/auth/me', [AuthController::class, 'me'])->middleware('auth:api');