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

    // tpa
    Route::get('/tpa', [\App\Http\Controllers\Admin\TpaController::class, 'index'])->name('tpa.index');

    // patient
    Route::get('/patients', [\App\Http\Controllers\Admin\PatientController::class, 'index'])->name('patient.index');

    // create patient
    Route::get('/patients/create', [\App\Http\Controllers\Admin\PatientController::class, 'create'])->name('patient.create');

    // edit patient
    Route::get('/patients/{id}/edit', [\App\Http\Controllers\Admin\PatientController::class, 'edit'])->name('patient.edit');

    // charge
    Route::prefix('charge')->name('charge.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ChargeController::class, 'charge'])->name('index');
        Route::get('/unit', [\App\Http\Controllers\Admin\ChargeController::class, 'unit'])->name('unit');
        Route::get('/tax-categories', [\App\Http\Controllers\Admin\ChargeController::class, 'taxCategories'])->name('tax-categories');
        Route::get('/charge-types', [\App\Http\Controllers\Admin\ChargeController::class, 'chargeTypes'])->name('charge-types');
        Route::get('/charge-categories', [\App\Http\Controllers\Admin\ChargeController::class, 'chargeCategories'])->name('charge-categories');
        Route::get('/tpa-charges', [\App\Http\Controllers\Admin\ChargeController::class, 'tpaCharges'])->name('tpa-charges');
    });


    // Medical Departments Group
    Route::prefix('departments')->group(function () {
        Route::get('/medical-departments', [\App\Http\Controllers\Admin\DepartmentController::class, 'medicalDepartmentIndex'])->name('medical-departments.index');
    });

    // Doctor & Specialist Management Group
    Route::prefix('doctors')->group(function () {
        // Specialist Master
        Route::get('/specialist', [\App\Http\Controllers\Admin\DoctorController::class, 'specialist'])->name('specialist.index');

        // Doctor CRUD
        Route::get('/', [\App\Http\Controllers\Admin\DoctorController::class, 'index'])->name('doctor.index');
        Route::get('/create', [\App\Http\Controllers\Admin\DoctorController::class, 'create'])->name('doctor.create');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\DoctorController::class, 'edit'])->name('doctor.edit');
    });

    // global shift
    Route::get('/global-shift', [\App\Http\Controllers\Admin\ShiftController::class, 'globalShift'])->name('globalshift.index');

    // doctor-schedules
    Route::prefix('doctor-schedules')->name('doctor-schedules.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ShiftController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ShiftController::class, 'create'])->name('create');
        Route::get('/{id}/edit/', [\App\Http\Controllers\Admin\ShiftController::class, 'create'])->name('create');
    });

    // appointment
    Route::prefix('appointment')->name('appointment.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AppointmentController::class, 'create'])->name('create');
        Route::get('/{id}/edit/', [\App\Http\Controllers\Admin\AppointmentController::class, 'create'])->name('create');
    });

    // Symptom Management Group
    Route::prefix('symptoms')->group(function () {
        Route::get('/types', [\App\Http\Controllers\Admin\SymptomController::class, 'SymptomType'])->name('symptom-types.index');
        Route::get('/titles', [\App\Http\Controllers\Admin\SymptomController::class, 'SymptomTitle'])->name('symptom-titles.index');
    });


    Route::prefix('opd')->name('opd.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\OpdController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\OpdController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\OpdController::class, 'edit'])->name('edit');
        Route::get('/{id}/show', [App\Http\Controllers\Admin\OpdController::class, 'show'])->name('show');
    });

    Route::prefix('pathology')->name('pathology.')->group(function () {
        Route::get('/units', [\App\Http\Controllers\Admin\PathologyController::class, 'unit'])->name('unit');
        Route::get('/categories', [\App\Http\Controllers\Admin\PathologyController::class, 'category'])->name('category');
        Route::get('/parameters', [\App\Http\Controllers\Admin\PathologyController::class, 'parameter'])->name('parameter');
        Route::get('/tests', [\App\Http\Controllers\Admin\PathologyController::class, 'test'])->name('test');
        
    });
});
