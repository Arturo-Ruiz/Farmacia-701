<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('show-login-form');

Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login');

Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

Route::post('/logout', [App\Http\Controllers\Admin\Auth\LogoutController::class, 'logout'])->name('logout');