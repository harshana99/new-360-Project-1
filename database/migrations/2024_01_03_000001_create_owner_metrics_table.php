<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('owner_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_properties_count')->default(0);
            $table->decimal('total_investments_received', 18, 2)->default(0);
            $table->decimal('total_earnings', 18, 2)->default(0);
            $table->integer('active_investments_count')->default(0);
            $table->integer('pending_approvals')->default(0);
            $table->decimal('monthly_earnings', 18, 2)->default(0);
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'calculated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_metrics');
    }
};
