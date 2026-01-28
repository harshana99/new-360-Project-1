<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Users Table Migration
 * 
 * This migration creates the users table with all necessary fields for
 * the 360WinEstate authentication and membership system.
 * 
 * Status Flow:
 * registered → membership_selected → kyc_submitted → under_review → approved/rejected
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Membership Information
            $table->enum('membership_type', ['owner', 'investor', 'marketer'])->nullable();
            
            // Status Tracking
            // Possible values: registered, membership_selected, kyc_submitted, under_review, approved, rejected
            $table->enum('status', [
                'registered',
                'membership_selected',
                'kyc_submitted',
                'under_review',
                'approved',
                'rejected'
            ])->default('registered');
            
            // Status timestamps for tracking
            $table->timestamp('membership_selected_at')->nullable();
            $table->timestamp('kyc_submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            // Rejection reason (if applicable)
            $table->text('rejection_reason')->nullable();
            
            // Remember token for "Remember Me" functionality
            $table->rememberToken();
            
            // Timestamps (created_at, updated_at)
            $table->timestamps();
            
            // Soft deletes (optional - allows user recovery)
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index('email');
            $table->index('status');
            $table->index('membership_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
