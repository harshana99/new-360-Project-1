<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Properties Table Migration
 * 
 * Creates the properties table with all fields for managing
 * real estate properties in the 360WinEstate platform.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('unit_number', 100)->unique();
            $table->string('location');
            $table->string('state', 100);
            $table->string('country', 100)->default('India');
            
            // Property Details
            $table->enum('property_type', ['flat', 'villa', 'service_apartment', 'commercial']);
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->decimal('square_feet', 10, 2);
            
            // Status & Pricing
            $table->enum('status', ['available', 'occupied', 'under_maintenance', 'listed', 'sold'])
                ->default('available');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('current_valuation', 15, 2);
            
            // Documents
            $table->string('cof_number', 100)->nullable()->unique();
            $table->string('cof_document_url')->nullable();
            $table->string('deed_document_url')->nullable();
            $table->string('allocation_letter_url')->nullable();
            
            // Description & Media
            $table->text('description')->nullable();
            $table->string('featured_image_url')->nullable();
            
            // Structured Data (JSON)
            $table->json('coordinates')->nullable(); // {lat: 0.0, lng: 0.0}
            $table->json('amenities')->nullable(); // ["wifi", "parking", "gym", ...]
            
            // Ownership & Management
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_managed')->default(false);
            $table->boolean('is_featured')->default(false);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index('property_type');
            $table->index('status');
            $table->index('location');
            $table->index('state');
            $table->index('country');
            $table->index('is_featured');
            $table->index('is_managed');
            $table->index(['current_valuation', 'property_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
