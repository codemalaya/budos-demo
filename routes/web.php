<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderDetailController;
use App\Http\Controllers\Admin\MenuVariantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicCartController;
use App\Http\Controllers\PublicOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicOrderController::class, 'index'])->name('landing');
Route::post('/cart', [PublicCartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{key}', [PublicCartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{key}', [PublicCartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [PublicCartController::class, 'clear'])->name('cart.clear');
Route::post('/order', [PublicCartController::class, 'checkout'])->name('orders.store.public');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/dashboard', 'admin.index')->name('dashboard');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('menus', MenuController::class);
        Route::resource('menus.variants', MenuVariantController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
            ->shallow();
        Route::resource('orders', OrderController::class);
        Route::resource('orders.details', OrderDetailController::class)
            ->only(['store', 'update', 'destroy'])
            ->shallow();
    });
});
