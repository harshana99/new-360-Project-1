<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\Admin\AdminKycController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

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

// Default route
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
