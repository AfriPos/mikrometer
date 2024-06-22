<?php

use App\Http\Controllers\api\RouterosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/test-api', [RouterosController::class, 'test_api']);
    Route::get('/routeros-connect', [RouterosController::class, 'routeros_connection']);
    Route::get('/set-interface', [RouterosController::class, 'set_interface']);
    Route::get('/add-ip-pool', [RouterosController::class, 'add_ip_pool']);
    Route::get('/create_pppoe', [RouterosController::class, 'create_pppoe']);
});