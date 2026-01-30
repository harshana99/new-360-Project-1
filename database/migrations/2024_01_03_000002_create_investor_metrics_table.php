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
        Schema::create('investor_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_invested', 18, 2)->default(0);
            $table->decimal('total_returns', 18, 2)->default(0);
            $table->decimal('total_roi_percentage', 8, 2)->default(0);
            $table->integer('active_investments')->default(0);
            $table->integer('completed_investments')->default(0);
            $table->decimal('pending_dividends', 18, 2)->default(0);
            $table->date('next_dividend_date')->nullable();
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
        Schema::dropIfExists('investor_metrics');
    }
};
