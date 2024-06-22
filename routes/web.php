<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PoolController;
use App\Http\Controllers\PPPoEServiceController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\CustomerSubscriptionController;
use App\Http\Controllers\PaymentController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ip pools
    // Route::get('/router', [RouterController::class, 'index'])->name('router.index');
    Route::get('/admin/pool/create', [PoolController::class, 'create'])->name('pool.create');
    Route::post('/admin/pool', [PoolController::class, 'store'])->name('pool.store');

    // Router
    Route::get('/admin/router', [RouterController::class, 'index'])->name('router.index');
    Route::get('/admin/router/create', [RouterController::class, 'create'])->name('router.create');
    Route::post('/admin/router', [RouterController::class, 'store'])->name('router.store');
    Route::get('/admin/router/{id}/edit', [RouterController::class, 'edit'])->name('router.edit');
    Route::put('/admin/router/{id}', [RouterController::class, 'update'])->name('router.update');
    Route::delete('/admin/router/{id}', [RouterController::class, 'destroy'])->name('router.destroy');
    Route::post('/fetch-interfaces', [RouterController::class, 'fetchInterfaces'])->name('fetch.interfaces');

    // PPPoE Service Routes
    Route::get('/admin/pppoe', [PPPoEServiceController::class, 'index'])->name('pppoe.index');
    Route::get('/admin/pppoe/create', [PPPoEServiceController::class, 'create'])->name('pppoe.create');
    Route::post('/admin/pppoe', [PPPoEServiceController::class, 'store'])->name('pppoe.store');
    Route::get('/admin/pppoe/{id}/edit', [PPPoEServiceController::class, 'edit'])->name('pppoe.edit');
    Route::put('/admin/pppoe/{id}', [PPPoEServiceController::class, 'update'])->name('pppoe.update');
    Route::delete('/admin/pppoe/{id}', [PPPoEServiceController::class, 'destroy'])->name('pppoe.destroy');


    // Customers
    Route::get('/admin/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('admin/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('admin/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('admin/customer/{customer}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('admin/customer/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('admin/customer/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Service
    Route::get('/admin/service', [CustomerSubscriptionController::class, 'index'])->name('service.index');
    Route::get('/admin/service/create', [CustomerSubscriptionController::class, 'create'])->name('service.create');
    Route::post('/admin/service/{customer}', [CustomerSubscriptionController::class, 'store'])->name('service.store');
    Route::get('/admin/service/{id}/edit', [CustomerSubscriptionController::class, 'edit'])->name('service.edit');
    Route::put('/admin/service/{id}', [CustomerSubscriptionController::class, 'update'])->name('service.update');
    Route::delete('/admin/service/{id}', [CustomerSubscriptionController::class, 'destroy'])->name('service.destroy');



    // Payment
    Route::get('/admin/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/admin/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/admin/payment', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/admin/dispatch', [PaymentController::class, 'dispatch'])->name('payment.dispatch');
});

require __DIR__ . '/auth.php';
