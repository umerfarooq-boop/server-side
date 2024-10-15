<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerParentController;
use App\Http\Controllers\SportCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/resend-otp',[AuthController::class,'resendOtp']);

Route::resources([
    'category' => SportCategoryController::class,
    'academy' => AcademyController::class,
    'coach'  => CoachController::class,
    'player' => PlayerController::class,
    'player_parent' => PlayerParentController::class,
    'profile' => ProfileController::class,
]);

