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
use App\Http\Controllers\VedioController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CoachScheduleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/resend-otp',[AuthController::class,'resendOtp']);

// Show coahc_record in front of Website
    Route::get('/coach_record',[CoachController::class,'coach_record']);
// Show coahc_record in front of Website

// Show Coach Post in Website
    Route::get('/showpost',[PostController::class,'showPost']);
// Show Coach Post in Website

// Show Blog posts of Coach According to its id show in slidder
    Route::get('/showBlogPost/{id}',[PostController::class,'showBlogPost']);
// Show Blog posts of Coach According to its id show in slidder

// Update Post
    Route::post('/updatePost/{id}',[PostController::class,'updatePost']);
// Update Post

// Get Coach Record
    // Route::get('/coachschedule',[CoachScheduleController::class,'coachschedule']);
// Get Coach Record

// Download File
Route::get('/DownloadFile/{path}/{filename}', [CoachController::class, 'DownloadFile']);
// Download File

// Change Account Status
Route::get('/changeStatus/{id}',[CoachController::class,'changeStatus']);
// Change Account Status

// Change Post Status
Route::get('/changePostStatus/{id}',[PostController::class,'changePostStatus']);
// Change Post Status

// Update the Record
Route::post('/updateRecord/{id}',[CoachController::class,'updateRecord']);
// Update the Record

Route::resources([
    'category' => SportCategoryController::class,
    'academy' => AcademyController::class,
    'coach'  => CoachController::class,
    'player' => PlayerController::class,
    'player_parent' => PlayerParentController::class,
    'profile' => ProfileController::class,
    'posts' => PostController::class,
    'vedio' => VedioController::class,
    'coachschedule' => CoachScheduleController::class
]);

