<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductPageController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('account')->with('success', 'Your email has been verified successfully!');
    })->middleware('signed')->name('verification.verify');

    // Profile Routes
    Route::controller(\App\Http\Controllers\ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::post('/update', 'updatePersonalInfo')->name('update');
        Route::post('/password', 'updatePassword')->name('password');
    });

    // Address Routes
    Route::controller(\App\Http\Controllers\AddressController::class)->prefix('addresses')->name('addresses.')->group(function () {
        Route::post('/', 'store')->name('store');
        Route::put('/{address}', 'update')->name('update');
        Route::delete('/{address}', 'destroy')->name('destroy');
        Route::post('/{address}/default', 'setDefault')->name('setDefault');
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

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/update', [SettingController::class, 'update'])->name('update');
    });

    // User Management Routes
    Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/admins', 'admins')->name('admins');
        Route::get('/customers', 'customers')->name('customers');
        Route::get('/{user}', 'show')->name('show');
    });

    // Stock Management Routes
    Route::resource('stock', StockController::class)->only(['index', 'update']);

    // Coupon Management Routes
    Route::get('coupons/bulk-generate', [CouponController::class, 'showBulkGenerate'])->name('coupons.bulk-generate');
    Route::post('coupons/bulk-generate', [CouponController::class, 'storeBulkGenerate'])->name('coupons.bulk-store');
    Route::resource('coupons', CouponController::class);
});


Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/community', function () {
    return view('community');
});

Route::get('/catalog', [ProductPageController::class, 'catalog'])->name('catalog');

Route::get('/products/{slug}', [ProductPageController::class, 'show'])->name('products.show');

// Cart & Checkout Routes
Route::prefix('cart')->name('cart.')->controller(CheckoutController::class)->group(function () {
    Route::get('/', 'getCart')->name('get');
    Route::post('/add', 'addToCart')->name('add');
    Route::put('/update', 'updateCart')->name('update');
    Route::delete('/remove', 'removeFromCart')->name('remove');
});

Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
    // Checkout pages - only accessible by guests and verified users
    Route::middleware(['check.checkout.access'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/process', 'process')->name('process');
        Route::post('/coupon', 'applyCoupon')->name('coupon.apply');
        Route::delete('/coupon', 'removeCoupon')->name('coupon.remove');
    });

    // Success page - accessible by anyone with the order ID
    Route::get('/success/{order}', 'success')->name('success');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/account', [AccountController::class, 'index'])->name('account');
