<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TrilaterationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;


// Authentication routes
Auth::routes();

// Protected routes group
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Menu Management
    Route::resource('menu', MenuController::class);
    Route::delete('/menu/{id}', [MenuController::class, 'destroy']);

    // Site Management
    Route::resource('sites', SiteController::class);
    Route::get('/sites/create', [SiteController::class, 'create'])->name('sites.create');
    Route::get('/sites/delete/{id}', [SiteController::class, 'destroy']);
    Route::get('/sites/edit/{id}', [SiteController::class, 'edit']);
    Route::post('/sites/update/{id}', [SiteController::class, 'update']);
    Route::get('/sites/show/{id}', [SiteController::class, 'show'])->name('sites.show');
    Route::get('/live', [SiteController::class, 'live'])->name('sites.live');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('auth.register');
    Route::post('/register', [RegisterController::class, 'register']);


    // Trilateration route
    Route::post('/trilateration/latest-position', [TrilaterationController::class, 'getLatestPosition']);
});

