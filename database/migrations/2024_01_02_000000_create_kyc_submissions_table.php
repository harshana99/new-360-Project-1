<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * KYC Submissions Table Migration
 * 
 * Stores KYC submission data for user verification
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kyc_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code', 20);
            $table->string('country');
            
            // ID Information
            $table->enum('id_type', ['passport', 'drivers_license', 'national_id', 'voter_id']);
            $table->string('id_number');
            $table->date('id_expiry_date')->nullable();
            
            // KYC Status
            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'approved',
                'rejected',
                'resubmission_required'
            ])->default('draft');
            
            // Review Information
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Resubmission tracking
            $table->integer('submission_count')->default(1);
            $table->foreignId('previous_submission_id')->nullable()->constrained('kyc_submissions')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_submissions');
    }
};
