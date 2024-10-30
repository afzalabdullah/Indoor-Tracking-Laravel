<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Menu;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TrilaterationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;


// Authentication routes
Auth::routes();
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('auth.register');
Route::post('/register', [RegisterController::class, 'register']);


// Protected routes group
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Menu Management
    Route::resource('menu', Menu::class);
    Route::delete('/menu/{id}', [Menu::class, 'destroy']);

    // Site Management
    Route::resource('sites', SiteController::class);
    Route::get('/sites/create', [SiteController::class, 'create'])->name('sites.create');
    Route::get('/sites/delete/{id}', [SiteController::class, 'destroy']);
    Route::get('/sites/edit/{id}', [SiteController::class, 'edit']);
    Route::post('/sites/update/{id}', [SiteController::class, 'update']);
    Route::get('/sites/show/{id}', [SiteController::class, 'show'])->name('sites.show');
    Route::get('/live', [SiteController::class, 'live'])->name('sites.live');

    // Trilateration route
    Route::post('/trilateration/latest-position', [TrilaterationController::class, 'getLatestPosition']);
});

