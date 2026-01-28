<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Activities Table Migration
 * 
 * Tracks all user and admin activities for audit trail
 * Used for: profile updates, password changes, KYC submissions, admin actions, etc.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            
            // User who performed the action
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Admin who performed the action (if admin action)
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            
            // Activity type
            $table->enum('activity_type', [
                'registration',
                'login',
                'logout',
                'profile_update',
                'password_changed',
                'kyc_submitted',
                'kyc_approved',
                'kyc_rejected',
                'kyc_resubmitted',
                'membership_selected',
                'account_suspended',
                'account_activated',
                'admin_created',
                'admin_updated',
                'admin_deactivated',
                'admin_activated',
                'user_updated_by_admin',
                'other'
            ])->default('other');
            
            // Detailed description of the activity
            $table->text('description');
            
            // IP address from where action was performed
            $table->string('ip_address', 45)->nullable();
            
            // User agent (browser info)
            $table->text('user_agent')->nullable();
            
            // Additional metadata (JSON)
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('admin_id');
            $table->index('activity_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
