<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/**
 * Admin Routes
 * 
 * Role-based admin routes with middleware protection
 * 
 * Admin Roles:
 * - super_admin: Full access + can create other admins
 * - compliance_admin: KYC review only
 * - finance_admin: Payments & commissions only
 * - content_admin: Projects & content only
 */

// Super Admin Only Routes
Route::middleware(['auth', 'check_admin_role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admins', [DashboardController::class, 'admins'])->name('admins');
    Route::get('/admins/create', [DashboardController::class, 'createAdminForm'])->name('create');
    Route::post('/admins', [DashboardController::class, 'storeAdmin'])->name('store');
    Route::get('/admins/{id}/edit', [DashboardController::class, 'editAdminForm'])->name('edit');
    Route::post('/admins/{id}', [DashboardController::class, 'updateAdmin'])->name('update');
    Route::post('/admins/{id}/deactivate', [DashboardController::class, 'deactivateAdmin'])->name('deactivate');
    Route::post('/admins/{id}/activate', [DashboardController::class, 'activateAdmin'])->name('activate');
});

// Compliance Admin Routes
Route::middleware(['auth', 'check_admin_role:compliance_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kyc', [DashboardController::class, 'kyc'])->name('kyc');
});

// Finance Admin Routes
Route::middleware(['auth', 'check_admin_role:finance_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/payments', [DashboardController::class, 'payments'])->name('payments');
    Route::get('/commissions', [DashboardController::class, 'commissions'])->name('commissions');
});

// Content Admin Routes
Route::middleware(['auth', 'check_admin_role:content_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/projects', [DashboardController::class, 'projects'])->name('projects');
});

// All Admin Types Can Access
Route::middleware(['auth', 'check_admin_role:super_admin,compliance_admin,finance_admin,content_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
});
