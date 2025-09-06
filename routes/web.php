<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\DayRateController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdController;




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

    // User management routes
    Route::resource('/admin/users', UserController::class)->names('admin.users');

    // Category management routes
    Route::resource('/admin/categories', CategoryController::class)->names('admin.categories');

    // Tax management routes
    Route::resource('/admin/taxes', TaxController::class)->names('admin.taxes');

    //Day Rates management routes
    Route::resource('/admin/day-rates', DayRateController::class)
        ->only(methods: ['index', 'show', 'update'])
        ->names(names: 'admin.day-rates');

    // Product management routes
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');

    // Product import route
    Route::post('/admin/products/import', [ProductController::class, 'import'])->name('admin.products.import');

    // Product image upload routes
    Route::get('/admin/products/upload-images', [ProductController::class, 'showUploadImages'])->name('admin.products.upload-images');

    Route::post('/admin/products/upload-images', [ProductController::class, 'uploadImages'])->name('admin.products.store-images');

    // Ad management routes  
    Route::get('/admin/ads', [AdController::class, 'index'])->name('admin.ads.index');
    Route::post('/admin/ads/upload-images', [AdController::class, 'uploadImages'])->name('admin.ads.store-images');
    Route::delete('/admin/ads/{id}', [AdController::class, 'destroy'])->name('admin.ads.destroy');



    // Logout route
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
