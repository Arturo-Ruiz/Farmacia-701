<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', function () {
    return view(view: 'welcome');
});


// Authentication Routes
Route::middleware(['guest'])->group(function () {
    
    // Show the login form
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

    // Handle the login request
    Route::post('/login', [LoginController::class, 'login'])->name('login');

});

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard route
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    
    // Logout route
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});