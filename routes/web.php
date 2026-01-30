<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\Admin\AdminKycController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\InvestorDashboardController;
use App\Http\Controllers\MarketerDashboardController;
use App\Http\Middleware\CheckUserRole;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;

/*
|--------------------------------------------------------------------------
| Web Routes - 360WinEstate Authentication Module
|--------------------------------------------------------------------------
|
| This file contains all routes for the authentication system including
| registration, login, email verification, and dashboard access.
|
*/

// Guest Routes (accessible only when not logged in)
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes (requires login)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Email Verification Routes
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        
        $user = $request->user();
        
        // Redirect based on user status after verification
        if (!$user->hasMembership()) {
            return redirect()->route('membership.select')
                ->with('success', 'Email verified successfully! Please select your membership type.');
        }
        
        if (!$user->isApproved()) {
            return redirect()->route('dashboard.locked')
                ->with('success', 'Email verified successfully!');
        }
        
        return redirect()->route('dashboard')
            ->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    // Membership Selection (requires verified email)
    Route::middleware('verified')->group(function () {
        Route::get('/membership/select', [MembershipController::class, 'showSelectionForm'])
            ->name('membership.select');
        Route::post('/membership/select', [MembershipController::class, 'selectMembership']);
    });

    // KYC Routes (requires verified email and membership)
    Route::middleware('verified')->group(function () {
        // KYC Submission
        Route::get('/kyc/submit', [KycController::class, 'create'])->name('kyc.create');
        Route::post('/kyc/submit', [KycController::class, 'store'])->name('kyc.store');
        Route::get('/kyc/status', [KycController::class, 'status'])->name('kyc.status');
        
        // KYC Resubmission
        Route::get('/kyc/resubmit', [KycController::class, 'resubmit'])->name('kyc.resubmit');
        Route::post('/kyc/resubmit', [KycController::class, 'storeResubmission'])->name('kyc.resubmit.store');
        
        // Document Download
        Route::get('/kyc/document/{document}/download', [KycController::class, 'downloadDocument'])
            ->name('kyc.document.download');
    });

    // Locked Dashboard (for non-approved users with verified email and membership)
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard/locked', [DashboardController::class, 'locked'])
            ->name('dashboard.locked');
    });

    // Main Dashboard (requires approved status)
    Route::middleware(['verified', 'check.approved'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
    });

    // Admin Routes (requires admin role)
    Route::prefix('admin')->name('admin.')->middleware(['verified', 'check.approved'])->group(function () {
        // KYC Management
        Route::get('/kyc', [AdminKycController::class, 'index'])->name('kyc.index');
        Route::get('/kyc/dashboard', [AdminKycController::class, 'dashboard'])->name('kyc.dashboard');
        Route::get('/kyc/{kycSubmission}', [AdminKycController::class, 'show'])->name('kyc.show');
        
        // KYC Actions
        Route::post('/kyc/{kycSubmission}/under-review', [AdminKycController::class, 'markUnderReview'])
            ->name('kyc.under-review');
        Route::post('/kyc/{kycSubmission}/approve', [AdminKycController::class, 'approve'])
            ->name('kyc.approve');
        Route::post('/kyc/{kycSubmission}/reject', [AdminKycController::class, 'reject'])
            ->name('kyc.reject');
        Route::post('/kyc/{kycSubmission}/request-resubmission', [AdminKycController::class, 'requestResubmission'])
            ->name('kyc.request-resubmission');
        
        // Document Actions
        Route::post('/kyc/document/{document}/verify', [AdminKycController::class, 'verifyDocument'])
            ->name('kyc.document.verify');
        Route::get('/kyc/document/{document}/view', [AdminKycController::class, 'viewDocument'])
            ->name('kyc.document.view');
        Route::get('/kyc/document/{document}/download', [AdminKycController::class, 'downloadDocument'])
            ->name('kyc.document.download');
        
        // Bulk Actions
        Route::post('/kyc/bulk/approve', [AdminKycController::class, 'bulkApprove'])
            ->name('kyc.bulk.approve');
        Route::post('/kyc/bulk/reject', [AdminKycController::class, 'bulkReject'])
            ->name('kyc.bulk.reject');
    });
});

    // --- MODULE 3: ROLE-BASED DASHBOARDS ---

    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Admin Redirect
        // Admin Redirect
        if ($user->admin || in_array($user->role, ['admin', 'super_admin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Role Redirect
        return match ($user->membership_type) {
            'owner' => redirect()->route('owner.dashboard'),
            'investor' => redirect()->route('investor.dashboard'),
            'marketer' => redirect()->route('marketer.dashboard'),
            default => redirect()->route('membership.select'),
        };
    })->name('dashboard');

    // Owner Routes
    Route::middleware(CheckUserRole::class.':owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/properties', [OwnerDashboardController::class, 'properties'])->name('properties');
        Route::get('/earnings', [OwnerDashboardController::class, 'earnings'])->name('earnings');
        Route::get('/documents', [OwnerDashboardController::class, 'documents'])->name('documents');
        Route::get('/analytics', [OwnerDashboardController::class, 'analytics'])->name('analytics');
    });

    // Investor Routes
    Route::middleware(CheckUserRole::class.':investor')->prefix('investor')->name('investor.')->group(function () {
        Route::get('/dashboard', [InvestorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/investments', [InvestorDashboardController::class, 'investments'])->name('investments');
        Route::get('/portfolio', [InvestorDashboardController::class, 'portfolio'])->name('portfolio');
        Route::get('/dividends', [InvestorDashboardController::class, 'dividends'])->name('dividends');
        Route::get('/performance', [InvestorDashboardController::class, 'performance'])->name('performance');
    });

    // Marketer Routes
    Route::middleware(CheckUserRole::class.':marketer')->prefix('marketer')->name('marketer.')->group(function () {
        Route::get('/dashboard', [MarketerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/team', [MarketerDashboardController::class, 'team'])->name('team');
        Route::get('/commissions', [MarketerDashboardController::class, 'commissions'])->name('commissions');
        Route::get('/leaderboard', [MarketerDashboardController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/targets', [MarketerDashboardController::class, 'targets'])->name('targets');
    });

    // --- MODULE 4: PROPERTY MANAGEMENT ---

    // Public Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('properties.show');

    // Owner Properties
    Route::middleware(CheckUserRole::class.':owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('/properties/list', [PropertyController::class, 'ownerList'])->name('properties');
        Route::get('/properties/create', [PropertyController::class, 'ownerCreate'])->name('properties.create');
        Route::post('/properties', [PropertyController::class, 'ownerStore'])->name('properties.store');
        Route::get('/properties/{id}/edit', [PropertyController::class, 'ownerEdit'])->name('properties.edit');
        Route::post('/properties/{id}', [PropertyController::class, 'ownerUpdate'])->name('properties.update');
        Route::post('/properties/{id}/delete', [PropertyController::class, 'ownerDelete'])->name('properties.delete');
    });

    // Admin Properties (Nested in Admin Prefix)
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
        Route::get('/properties/{id}', [AdminPropertyController::class, 'show'])->name('properties.show');
        Route::post('/properties/{id}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
        Route::post('/properties/{id}/reject', [AdminPropertyController::class, 'reject'])->name('properties.reject');
    });
    

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
