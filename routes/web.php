<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\CustomerLoginController;
use App\Http\Controllers\BandwidthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PoolController;
use App\Http\Controllers\PPPoEServiceController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\CustomerSubscriptionController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\hotspotController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\locationsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\radacctController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\routerSyncController;

Route::get('/', function () {
    return redirect('/portal');
});


Route::get('bundles', [hotspotController::class, 'bundles'])->name('hotspot.bundles');
Route::post('/purchase', [hotspotController::class, 'purchase'])->name('purchase');
Route::get('/hotspot/redirect', [HotspotController::class, 'redirect'])->name('hotspot.redirect');



Route::get('/hlogin', function () {
    return view('hotspot.login');
})->name('hlogin');
// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Customer Authentication
Route::get('customer/login', [CustomerLoginController::class, 'create'])->name('customer.login');
Route::post('customer/login', [CustomerLoginController::class, 'store']);
Route::post('customer/logout', [CustomerLoginController::class, 'destroy'])->name('customer.logout');


Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [dashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ip pools
    // Route::get('/router', [RouterController::class, 'index'])->name('router.index');
    Route::get('/admin/pool/create', [PoolController::class, 'create'])->name('pool.create');
    Route::post('/admin/pool', [PoolController::class, 'store'])->name('pool.store');

    // Router
    Route::get('/admin/routers', [RouterController::class, 'index'])->name('router.index');
    Route::get('/admin/router/create', [RouterController::class, 'create'])->name('router.create');
    Route::post('/admin/router', [RouterController::class, 'store'])->name('router.store');
    Route::get('/admin/router/{nas}/edit', [RouterController::class, 'edit'])->name('router.edit');
    Route::put('/admin/router/{nas}', [RouterController::class, 'update'])->name('router.update');
    Route::delete('/admin/router/{nas}', [RouterController::class, 'destroy'])->name('router.destroy');
    Route::post('/fetch-interfaces', [RouterController::class, 'fetchInterfaces'])->name('fetch.interfaces');

    // PPPoE Service Routes
    Route::get('/admin/pppoe', [PPPoEServiceController::class, 'index'])->name('pppoe.index');
    Route::get('/admin/pppoe/create', [PPPoEServiceController::class, 'create'])->name('pppoe.create');
    Route::post('/admin/pppoe', [PPPoEServiceController::class, 'store'])->name('pppoe.store');
    Route::get('/admin/pppoe/{id}/edit', [PPPoEServiceController::class, 'edit'])->name('pppoe.edit');
    Route::put('/admin/pppoe/{id}', [PPPoEServiceController::class, 'update'])->name('pppoe.update');
    Route::delete('/admin/pppoe/{id}', [PPPoEServiceController::class, 'destroy'])->name('pppoe.destroy');
    Route::post('/fetch-service', [PPPoEServiceController::class, 'show'])->name('pppoe.show');

    // Hotspot
    Route::get('/admin/hotspot', [hotspotController::class, 'index'])->name('hotspot.index');
    Route::get('/admin/hotspot/create', [hotspotController::class, 'create'])->name('hotspot.create');
    Route::post('/admin/hotspot', [hotspotController::class, 'store'])->name('hotspot.store');
    Route::get('/admin/hotspot/{id}', [hotspotController::class, 'show'])->name('hotspot.show');
    Route::get('/admin/hotspot/{id}/edit', [hotspotController::class, 'edit'])->name('hotspot.edit');
    Route::put('/admin/hotspot/{id}', [hotspotController::class, 'update'])->name('hotspot.update');
    Route::delete('/admin/hotspot/{id}', [hotspotController::class, 'destroy'])->name('hotspot.destroy');

    // Customers
    Route::get('/admin/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('admin/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('admin/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('admin/customer/{customer}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('admin/customer/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('admin/customer/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Service
    Route::get('/admin/service', [CustomerSubscriptionController::class, 'index'])->name('service.index');
    Route::get('/admin/service/create', [CustomerSubscriptionController::class, 'create'])->name('service.create');
    Route::post('/admin/service/{customer}', [CustomerSubscriptionController::class, 'store'])->name('service.store');
    Route::get('/admin/service/{subscriptionid}/edit', [CustomerSubscriptionController::class, 'edit'])->name('service.edit');
    Route::put('/admin/service/{subscriptionid}', [CustomerSubscriptionController::class, 'update'])->name('service.update');
    Route::delete('/admin/service/{subscriptionid}', [CustomerSubscriptionController::class, 'destroy'])->name('service.destroy');
    Route::post('/fetch-subscription', [CustomerSubscriptionController::class, 'show'])->name('service.show');

    // Payment
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/admin/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/admin/payment/{customer}', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/admin/payment/{payment}/edit', [PaymentController::class, 'edit'])->name('payment.edit');
    Route::put('/admin/payment/{payment}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/admin/payment/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');
    Route::get('/admin/dispatch', [PaymentController::class, 'dispatch'])->name('payment.dispatch');
    Route::get('payment/pdf/{id}', [PaymentController::class, 'generatePDF'])->name('payment.pdf');

    // Invoice
    Route::get('/admin/invoices', [invoiceController::class, 'index'])->name('invoice.index');
    Route::get('/admin/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('/admin/invoice/{customer}', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/admin/invoice/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/admin/invoice/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::put('/admin/invoice/{invoice}', [InvoiceController::class, 'update'])->name('invoice.update');
    Route::delete('/admin/invoice/{invoice}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
    Route::get('invoice/pdf/{id}', [InvoiceController::class, 'generatePDF'])->name('invoice.pdf');



    // others
    Route::get('/sse', 'App\Http\Controllers\SSEController@stream');
    Route::get('/ping', 'App\Http\Controllers\RouterController@pingInitialize');
    Route::post('/admin/active-session', [radacctController::class, 'show'])->name('radacct.show');
    Route::get('/admin/data-totals/{username}/{startDate}/{endDate}', [radacctController::class, 'getDataTotals'])->name('radacct.getDataTotals');
    Route::get('/admin/ended-sessions/{username}', [radacctController::class, 'showEndedSessions'])->name('radacct.ended-sessions');
    Route::get('/bandwidth/average', [BandwidthController::class, 'getAverageBandwidth'])->name('bandwidth.average');
    Route::get('/bandwidth/daily', [BandwidthController::class, 'getTotalDailyBandwidth'])->name('bandwidth.total-daily');

    // Accounting
    Route::post('/admin/active-session', [radacctController::class, 'show'])->name('radacct.show');

    // administration
    Route::get('/admin/administration', [AdminController::class, 'show'])->name('admin.show');
    Route::get('/ping', 'App\Http\Controllers\RouterController@pingInitialize');
    Route::post('/admin/active-session', [radacctController::class, 'show'])->name('radacct.show');
    Route::get('/admin/data-totals/{username}/{startDate}/{endDate}', [radacctController::class, 'getDataTotals'])->name('radacct.getDataTotals');
    Route::get('/admin/ended-sessions/{username}', [radacctController::class, 'showEndedSessions'])->name('radacct.ended-sessions');
    Route::get('/bandwidth/average/{username}', [BandwidthController::class, 'getAverageBandwidth']);
    Route::get('/bandwidth/daily', [BandwidthController::class, 'getTotalDailyBandwidth'])->name('bandwidth.total-daily');

    // SYNC ROUTER
    Route::post('/admin/router-sync/universal-coa', [routerSyncController::class, 'universalCoa'])->name('router.sync.universal-coa');




    // RoleController
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:read roles');
    Route::get('/admin/roles/{role}/editpermissions', [RoleController::class, 'editPermissions'])->name('roles.editpermissions')->middleware('permission:editpermissions roles');
    Route::put('/admin/roles/{role}/editpermissions', [RoleController::class, 'givePermissionsToRole'])->name('roles.givePermissionsToRole')->middleware('permission:editpermissions roles');

    // users
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Locations
    Route::get('/admin/locations', [locationsController::class, 'index'])->name('locations.index');
    Route::get('/admin/locations/create', [locationsController::class, 'create'])->name('locations.create');
    Route::post('/admin/locations', [locationsController::class, 'store'])->name('locations.store');
    Route::get('/admin/locations/{location}', [locationsController::class, 'show'])->name('locations.show');
    Route::get('/admin/locations/{location}/edit', [locationsController::class, 'edit'])->name('locations.edit');
    Route::put('/admin/locations/{location}', [locationsController::class, 'update'])->name('locations.update');
    Route::delete('/admin/locations/{location}', [locationsController::class, 'destroy'])->name('locations.destroy');
});

require __DIR__ . '/auth.php';
