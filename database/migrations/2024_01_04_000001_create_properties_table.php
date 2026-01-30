<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->longText('description');
            $table->string('location');
            $table->string('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->enum('property_type', ['residential', 'commercial', 'mixed_use', 'land'])->default('residential');
            $table->enum('status', ['pending', 'active', 'sold', 'inactive', 'under_review', 'rejected'])->default('pending');
            $table->decimal('price', 18, 2);
            $table->decimal('minimum_investment', 18, 2);
            $table->decimal('expected_return_percentage', 8, 2);
            $table->integer('lease_duration_months')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('currency')->default('NGN');
            
            // Admin approval
            $table->unsignedBigInteger('admin_approved_by')->nullable();
            $table->foreign('admin_approved_by')->references('id')->on('admins')->onDelete('set null');
            
            $table->timestamp('admin_approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
            $table->index('location');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
