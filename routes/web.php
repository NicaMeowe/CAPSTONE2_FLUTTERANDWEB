<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});

Route::middleware('admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/visits', [DashboardController::class, 'visits'])->name('visits');
    Route::get('/representatives', [DashboardController::class, 'representatives'])->name('representatives');
    Route::get('/facilities', [DashboardController::class, 'facilities'])->name('facilities');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/alerts', [DashboardController::class, 'alerts'])->name('alerts');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');

    Route::post('/admin/alerts/{alertId}/status', [DashboardController::class, 'updateAlert'])
        ->where('alertId', '[A-Za-z0-9_-]+')
        ->name('admin.alerts.status');
    Route::post('/admin/visits/{visitId}/review', [DashboardController::class, 'updateVisit'])
        ->where('visitId', '[A-Za-z0-9_-]+')
        ->name('admin.visits.review');
});
