<?php

use Illuminate\Support\Facades\Route;
use App\Console\Commands\AutoMarkAbsent;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-auto-attendance', function () {
    (new AutoMarkAbsent())->handle();
    return 'Auto attendance check complete.';
});