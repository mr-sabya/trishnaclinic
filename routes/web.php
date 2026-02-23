<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Backend\AuthController::class, 'login'])->name('login');
});
