<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

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

    Route::get('/admin/dashboard', function () {
        return 'Admin Dashboard';
    })->name('admin.dashboard');
    


    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});