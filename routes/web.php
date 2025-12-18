<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuVariantController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'public.index')->name('landing');

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
        Route::resource('menus', MenuController::class)->except(['show']);
        Route::resource('menus.variants', MenuVariantController::class)
            ->only(['index', 'create', 'store'])
            ->shallow();
        Route::resource('variants', MenuVariantController::class)
            ->only(['edit', 'update', 'destroy'])
            ->names([
                'edit' => 'variants.edit',
                'update' => 'variants.update',
                'destroy' => 'variants.destroy',
            ]);
    });
});
