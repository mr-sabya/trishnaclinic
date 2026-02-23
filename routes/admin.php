<?php

use Illuminate\Support\Facades\Route;



Route::middleware(['auth', 'checkRole:superadmin'])->group(function () {
    // Admin Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

    // users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');

    // create user
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');

    // edit user
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');

    // admin department index
    Route::get('/admin-departments', [\App\Http\Controllers\Admin\DepartmentController::class, 'adminDepartmentIndex'])->name('admin-departments.index');
});
