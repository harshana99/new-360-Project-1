<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dashboard Statistics Tables
 * 
 * Stores statistics for each user role's dashboard
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Owner Statistics
        Schema::create('owner_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Property Statistics
            $table->integer('properties_count')->default(0);
            $table->decimal('total_property_value', 15, 2)->default(0);
            $table->decimal('rental_income', 15, 2)->default(0);
            $table->integer('maintenance_tickets')->default(0);
            $table->integer('service_apartment_enrollments')->default(0);
            
            // Additional metrics
            $table->integer('active_properties')->default(0);
            $table->integer('rented_properties')->default(0);
            $table->decimal('monthly_revenue', 15, 2)->default(0);
            
            $table->timestamps();
            
            $table->unique('user_id');
        });

        // Investor Statistics
        Schema::create('investor_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Investment Statistics
            $table->integer('investments_count')->default(0);
            $table->decimal('total_invested', 15, 2)->default(0);
            $table->decimal('total_roi', 15, 2)->default(0);
            $table->decimal('roi_percentage', 5, 2)->default(0);
            $table->integer('projects_funded')->default(0);
            $table->decimal('wallet_balance', 15, 2)->default(0);
            $table->decimal('portfolio_value', 15, 2)->default(0);
            
            // Additional metrics
            $table->integer('active_investments')->default(0);
            $table->decimal('monthly_returns', 15, 2)->default(0);
            $table->integer('completed_projects')->default(0);
            
            $table->timestamps();
            
            $table->unique('user_id');
        });

        // Marketer Statistics
        Schema::create('marketer_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Referral Statistics
            $table->integer('total_referrals')->default(0);
            $table->integer('verified_referrals')->default(0);
            $table->integer('converted_sales')->default(0);
            $table->decimal('commissions_earned', 15, 2)->default(0);
            $table->integer('team_size')->default(0);
            
            // Additional metrics
            $table->integer('pending_referrals')->default(0);
            $table->decimal('pending_commissions', 15, 2)->default(0);
            $table->decimal('this_month_commissions', 15, 2)->default(0);
            $table->integer('this_month_referrals')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketer_stats');
        Schema::dropIfExists('investor_stats');
        Schema::dropIfExists('owner_stats');
    }
};
