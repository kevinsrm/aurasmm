<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CouponController;
use App\Http\Middleware\AdminMiddleware;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ApiController::class, 'index']);
    Route::get('/services', [ApiController::class, 'index'])->name('services');
    Route::post('/order', [ApiController::class, 'storeOrder'])->name('order.store');
    Route::get('/orders', [ApiController::class, 'ordersIndex'])->name('orders.index');
    Route::post('/orders/{id}/status', [ApiController::class, 'checkStatus'])->name('orders.status');
    Route::get('/api/balance', [ApiController::class, 'getBalance'])->name('api.balance');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Deposit Routes
    Route::get('/deposit', [DepositController::class, 'show'])->name('deposit.show');
    Route::post('/deposit', [DepositController::class, 'store'])->name('deposit.store');
    Route::get('/deposit/{id}/payment', [DepositController::class, 'showPayment'])->name('deposit.showPayment');
    Route::get('/deposit/{id}/status', [DepositController::class, 'checkStatus'])->name('deposit.checkStatus');
    
    // Coupon Routes
    Route::post('/coupon/redeem', [CouponController::class, 'redeem'])->name('coupon.redeem');

    // Admin Routes
    Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{id}/balance', [AdminController::class, 'updateUserBalance'])->name('admin.users.balance');
        Route::post('/users/{id}/admin', [AdminController::class, 'toggleAdmin'])->name('admin.users.toggleAdmin');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        
        Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('/refunds', [AdminController::class, 'refunds'])->name('admin.refunds');
        Route::post('/orders/{id}/refund', [AdminController::class, 'refundOrder'])->name('admin.orders.refund');
        Route::post('/orders/{id}/unrefund', [AdminController::class, 'unrefundOrder'])->name('admin.orders.unrefund');
        
        Route::get('/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
        Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('admin.coupons.store');
        Route::delete('/coupons/{id}', [AdminController::class, 'deleteCoupon'])->name('admin.coupons.delete');
        
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    });
});
