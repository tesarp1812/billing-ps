<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\DashboardSummaryController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\NamaCustomerController;

Route::get('/health', HealthController::class);

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/summary', DashboardSummaryController::class);
    Route::get('/stations', [StationController::class, 'index']);
    Route::post('/stations/{station}/start', [StationController::class, 'start']);
    Route::post('/stations/{station}/pause', [StationController::class, 'pause']);
    Route::post('/stations/{station}/stop', [StationController::class, 'stop']);
    Route::post('/stations/{station}/add-time', [StationController::class, 'addTime']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/checkout', CheckoutController::class);
    Route::get('/reports/daily', [ReportController::class, 'daily']);

    Route::get('/settings', [SettingsController::class, 'index']);
    Route::put('/settings', [SettingsController::class, 'update']);
    Route::post('/settings/reset', [SettingsController::class, 'reset']);

    Route::get('/nama-customer', [NamaCustomerController::class, 'index']);
});
