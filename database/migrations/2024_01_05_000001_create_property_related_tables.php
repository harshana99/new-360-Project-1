<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Property Related Tables Migration
 * 
 * Creates tables for ownership, maintenance, bookings, and listings
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ownerships Table
        Schema::create('ownerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('ownership_percentage', 5, 2)->default(100.00);
            $table->decimal('investment_amount', 15, 2);
            $table->enum('status', ['pending', 'active', 'sold', 'transferred'])->default('pending');
            $table->timestamp('acquired_date');
            $table->timestamp('sold_date')->nullable();
            $table->string('ownership_certificate_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['property_id', 'status']);
            $table->index(['user_id', 'status']);
        });

        // Maintenance Tickets Table
        Schema::create('maintenance_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('category', [
                'plumbing', 'electrical', 'hvac', 'structural', 
                'appliance', 'cleaning', 'pest_control', 'other'
            ])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'on_hold', 'completed', 'cancelled'])
                ->default('open');
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->timestamp('scheduled_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['property_id', 'status']);
            $table->index(['status', 'priority']);
        });

        // Service Apartment Bookings Table
        Schema::create('service_apartment_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->constrained('users')->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('number_of_guests')->default(1);
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('cleaning_fee', 10, 2)->nullable();
            $table->decimal('service_fee', 10, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])
                ->default('pending');
            $table->text('special_requests')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'refunded', 'failed'])->default('pending');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['property_id', 'status']);
            $table->index(['check_in_date', 'check_out_date']);
        });

        // Market Listings Table
        Schema::create('market_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->enum('listing_type', ['sale', 'auction', 'fractional'])->default('sale');
            $table->decimal('asking_price', 15, 2);
            $table->decimal('minimum_bid', 15, 2)->nullable();
            $table->decimal('current_bid', 15, 2)->nullable();
            $table->foreignId('highest_bidder_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'active', 'pending', 'sold', 'expired', 'cancelled'])
                ->default('draft');
            $table->text('description')->nullable();
            $table->timestamp('auction_start_date')->nullable();
            $table->timestamp('auction_end_date')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('inquiries_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'listing_type']);
            $table->index(['property_id', 'status']);
        });

        // Property Images Table
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('image_url');
            $table->string('title')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index(['property_id', 'order']);
        });

        // Property Reviews Table
        Schema::create('property_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('rating')->unsigned(); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            
            $table->index(['property_id', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_reviews');
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('market_listings');
        Schema::dropIfExists('service_apartment_bookings');
        Schema::dropIfExists('maintenance_tickets');
        Schema::dropIfExists('ownerships');
    }
};
