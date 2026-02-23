<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkRole:superadmin'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});
