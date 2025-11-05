<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductPageController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate')->name('login.auth');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'store')->name('register.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::delete('/products/{image}/delete-image', [ProductController::class, 'deleteImage'])->name('products.delete-image');
});


Route::get('/', function () {
    return view('home');
});

Route::get('/community', function () {
    return view('community');
});

Route::get('/catalog', [ProductPageController::class, 'catalog'])->name('catalog');

Route::get('/products/{slug}', [ProductPageController::class, 'show'])->name('products.show');

Route::get('/about', function () {
    return view('about');
});

Route::get('/account', function () {
    return view('account');
});
