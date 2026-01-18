<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\DriverOrderController;

// Public controllers
use App\Http\Controllers\ProizvodController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\CountryTownController;
use App\Http\Controllers\FakePayController;

// Admin controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| ADMIN AREA (prefix /admin, only for admin users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::resource('users', AdminUserController::class)->only(['index', 'show']);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);

        Route::put('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])
            ->name('orders.cancel');

        Route::put('/orders/{order}/close', [AdminOrderController::class, 'close'])
            ->name('orders.close');
    });

/*
|--------------------------------------------------------------------------
| SPECIAL ADMIN LOGIN ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/admin-login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin-login', [AdminAuthController::class, 'login'])
    ->name('admin.login.post');

Route::post('/admin-logout', [AdminAuthController::class, 'logout'])
    ->middleware(['auth', 'admin'])
    ->name('admin.logout');

/*
|--------------------------------------------------------------------------
| USER-FACING ROUTES (Public storefront)
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [ProizvodController::class, 'home'])->name('index.index');

// Products & categories
Route::get('/proizvodi', [ProizvodController::class, 'list'])->name('proizvodi.index');
Route::get('/kategorija/{id}', [ProizvodController::class, 'kategorija'])->name('proizvodi.kategorija');
Route::get('/proizvodi/kategorija/{id}', [ProizvodController::class, 'kategorija'])
    ->name('proizvodi.kategorija');


// AJAX search
Route::get('/ajax/proizvodi', [ProizvodController::class, 'ajaxSearch'])->name('proizvodi.search');
Route::get('/countries/search', [CountryController::class, 'search'])->name('countries.search');
Route::get('/towns/search', [CountryTownController::class, 'search'])->name('towns.search');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Single product page
Route::get('/proizvod/{id}', [ProizvodController::class, 'show'])->name('proizvod.show');

// User dashboard after login
Route::get('/dashboard', function () {
    if (Auth::user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/onboarding', [OnboardingController::class, 'show'])
    ->name('onboarding.show');

    Route::post('/onboarding', [OnboardingController::class, 'store'])
    ->name('onboarding.store');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manage addresses
    Route::post('/profile/address/{id}/default', [ProfileController::class, 'setDefaultAddress'])
        ->name('profile.address.default');

    Route::post('/profile/address/add', [ProfileController::class, 'addAddress'])
        ->name('profile.address.add');

    Route::post('/profile/address/delete', [ProfileController::class, 'deleteAddress'])
        ->name('profile.address.delete');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // FakePay
    Route::get('/payments/fakepay/{payment}', [FakePayController::class, 'show'])
        ->name('payments.fakepay');

    Route::post('/payments/fakepay/{payment}/process', [FakePayController::class, 'process'])
        ->name('payments.fakepay.process');

    Route::get('/payments/fakepay/{payment}/callback', [FakePayController::class, 'callback'])
        ->name('payments.fakepay.callback');
});

/*
|--------------------------------------------------------------------------
| DRIVER API ROUTES (removed duplicates)
|--------------------------------------------------------------------------
*/
Route::get('/driver/orders/new', [DriverOrderController::class, 'latestNewOrder']);
Route::get('/driver/orders', [DriverOrderController::class, 'index']);
Route::get('/driver/orders/{id}', [DriverOrderController::class, 'show']);

Route::post('/driver/orders/{id}/delivered', [DriverOrderController::class, 'markDelivered']);
Route::post('/driver/orders/{id}/not-delivered', [DriverOrderController::class, 'markNotDelivered']);

// Laravel Breeze / Jetstream / Fortify auth routes
require __DIR__ . '/auth.php';
