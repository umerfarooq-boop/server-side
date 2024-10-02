<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Route::middleware('auth:api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/resend-otp',[AuthController::class,'resendOtp']);
// });
