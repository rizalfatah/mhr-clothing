<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
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

    // Product image delete route must be BEFORE resource route to avoid conflict
    Route::delete('/products/images/{productImage}', [ProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::resource('products', ProductController::class);

    // Transaction Routes
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/{order}', [TransactionController::class, 'show'])->name('show');
        Route::put('/{order}/status', [TransactionController::class, 'updateStatus'])->name('update-status');
        Route::put('/{order}/shipping', [TransactionController::class, 'updateShipping'])->name('update-shipping');
        Route::put('/{order}/customer', [TransactionController::class, 'updateCustomer'])->name('update-customer');
        Route::put('/{order}/notes', [TransactionController::class, 'updateNotes'])->name('update-notes');
        Route::put('/{order}/cancel', [TransactionController::class, 'cancel'])->name('cancel');
        Route::delete('/{order}', [TransactionController::class, 'destroy'])->name('destroy');
        Route::get('/{order}/invoice', [TransactionController::class, 'printInvoice'])->name('invoice');
    });
});


Route::get('/', function () {
    return view('home');
})->name('home');

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
