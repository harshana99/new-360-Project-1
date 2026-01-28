<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AuthController;
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

// Admin Authentication Routes (No middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// All Admin Types - Dashboard (must be first to avoid conflicts)
Route::middleware(['auth', 'check_admin_role:super_admin,compliance_admin,finance_admin,content_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard - accessible by all admin types
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Common routes for all admins
        Route::get('/users', [UserManagementController::class, 'listUsers'])->name('users');
        Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
        
        // User Management Routes (MODULE 11)
        Route::prefix('user-management')->name('users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'listUsers'])->name('index');
            Route::get('/{id}', [UserManagementController::class, 'userDetails'])->name('show');
            Route::get('/{id}/edit', [UserManagementController::class, 'editUserForm'])->name('edit');
            Route::post('/{id}/update', [UserManagementController::class, 'updateUser'])->name('update');
            Route::post('/{id}/suspend', [UserManagementController::class, 'suspendUser'])->name('suspend');
            Route::post('/{id}/activate', [UserManagementController::class, 'activateUser'])->name('activate');
            Route::get('/{id}/kyc', [UserManagementController::class, 'viewUserKYC'])->name('kyc');
            Route::get('/export/csv', [UserManagementController::class, 'exportUsers'])->name('export');
        });
    });

// Super Admin Only Routes
Route::middleware(['auth', 'check_admin_role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/admins', [DashboardController::class, 'admins'])->name('admins');
        Route::get('/admins/create', [DashboardController::class, 'createAdminForm'])->name('create');
        Route::post('/admins', [DashboardController::class, 'storeAdmin'])->name('store');
        Route::get('/admins/{id}/edit', [DashboardController::class, 'editAdminForm'])->name('edit');
        Route::post('/admins/{id}', [DashboardController::class, 'updateAdmin'])->name('update');
        Route::post('/admins/{id}/deactivate', [DashboardController::class, 'deactivateAdmin'])->name('deactivate');
        Route::post('/admins/{id}/activate', [DashboardController::class, 'activateAdmin'])->name('activate');
        
        // Super Admin User Management (Delete & Restore)
        Route::post('/user-management/{id}/delete', [UserManagementController::class, 'deleteUser'])->name('users.delete');
        Route::post('/user-management/{id}/restore', [UserManagementController::class, 'restoreUser'])->name('users.restore');
    });

// Compliance Admin Routes
Route::middleware(['auth', 'check_admin_role:super_admin,compliance_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/kyc', [DashboardController::class, 'kyc'])->name('kyc');
        // KYC Actions
        Route::post('/kyc/{kycSubmission}/approve', [\App\Http\Controllers\Admin\AdminKycController::class, 'approve'])->name('kyc.approve');
        Route::post('/kyc/{kycSubmission}/reject', [\App\Http\Controllers\Admin\AdminKycController::class, 'reject'])->name('kyc.reject');
    });

// Finance Admin Routes
Route::middleware(['auth', 'check_admin_role:super_admin,finance_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/payments', [DashboardController::class, 'payments'])->name('payments');
        Route::get('/commissions', [DashboardController::class, 'commissions'])->name('commissions');
    });

// Content Admin Routes
Route::middleware(['auth', 'check_admin_role:super_admin,content_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/projects', [DashboardController::class, 'projects'])->name('projects');
    });
