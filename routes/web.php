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
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\LaboratoryController;
use App\Http\Controllers\Admin\ClientController;

use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Web\OrderController;


Route::redirect('/', '/Inicio', 301);

Route::get('/Inicio', [WebController::class, 'home'])->name('web.home');
Route::get('/productos/cargar-mas', [WebController::class, 'loadMoreProducts'])->name('web.products.load-more');
Route::get('/buscar', [WebController::class, 'search'])->name('web.search');
Route::get('/buscar/cargar-mas', [WebController::class, 'loadMoreSearchResults'])->name('web.search.load-more');
Route::get('/Laboratorio/{keyword}', [WebController::class, 'laboratory'])->name('web.laboratory');
Route::get('/carrito-de-compras', [WebController::class, 'cart'])->name('web.cart');
Route::post('/cart/process-order', [OrderController::class, 'processOrder'])->name('web.cart.process-order');

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
    Route::get('/admin/ads/{id}', [AdController::class, 'show'])->name('admin.ads.show');
    Route::post('/admin/ads/upload-images', [AdController::class, 'uploadImages'])->name('admin.ads.store-images');
    Route::put('/admin/ads/{id}', [AdController::class, 'update'])->name('admin.ads.update');
    Route::delete('/admin/ads/{id}', [AdController::class, 'destroy'])->name('admin.ads.destroy');

    // Carousel management routes  
    Route::resource('/admin/carousels', CarouselController::class)->names('admin.carousels');

    // Laboratory management routes  
    Route::resource('/admin/laboratories', LaboratoryController::class)->names('admin.laboratories');

    // Client management routes
    Route::resource('/admin/clients', ClientController::class)->only(['index', 'show', 'update'])->names('admin.clients');
    Route::get('/admin/clients/{client}/purchases', [ClientController::class, 'showPurchases'])->name('admin.clients.purchases');

    // Logout route
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
