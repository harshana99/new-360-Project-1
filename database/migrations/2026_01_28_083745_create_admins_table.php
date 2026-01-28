<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admins Table Migration
 * 
 * Creates the admins table for role-based admin system
 * 
 * Admin Roles:
 * - super_admin: Full platform control + can create other admins
 * - compliance_admin: KYC review and approval only
 * - finance_admin: Payments, commissions, financial reports
 * - content_admin: Projects, property listings, content management
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            
            // Link to user account
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Admin role - defines what this admin can access
            $table->enum('admin_role', [
                'super_admin',      // Full access + can create other admins
                'compliance_admin', // KYC review only
                'finance_admin',    // Payments & commissions only
                'content_admin'     // Projects & content only
            ]);
            
            // Admin status
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Track who created this admin (only super admin can create admins)
            // NULL for the first super admin (created via seeder)
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Track last login for audit purposes
            $table->timestamp('last_login')->nullable();
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('user_id');
            $table->index('admin_role');
            $table->index('status');
            $table->index('created_by');
            
            // Ensure one user can only have one admin role
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
