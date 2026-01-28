<?php

use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

/**
 * User Routes
 * 
 * All routes for authenticated users to manage their profiles,
 * KYC submissions, and view account activity.
 * 
 * Middleware: auth (must be logged in)
 */

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'editProfileForm'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Password Management
    Route::get('/profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.submit');
    
    // KYC Management
    Route::get('/kyc-status', [ProfileController::class, 'viewKYCStatus'])->name('kyc.status');
    Route::get('/kyc/resubmit', [ProfileController::class, 'resubmitKYCForm'])->name('kyc.resubmit');
    Route::post('/kyc/resubmit', [ProfileController::class, 'submitResubmittedKYC'])->name('kyc.resubmit.submit');
    Route::get('/kyc/download-documents', [ProfileController::class, 'downloadKYCDocuments'])->name('kyc.download');
    
    // Activity Log
    Route::get('/account-activity', [ProfileController::class, 'viewAccountActivity'])->name('activity');
});
