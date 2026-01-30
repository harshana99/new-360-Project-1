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
        Schema::create('marketer_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_referrals')->default(0);
            $table->decimal('total_commission', 18, 2)->default(0);
            $table->integer('active_team_members')->default(0);
            $table->decimal('pending_commission', 18, 2)->default(0);
            $table->decimal('monthly_commission', 18, 2)->default(0);
            $table->enum('commission_level', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
            $table->integer('team_tree_depth')->default(0);
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
        Schema::dropIfExists('marketer_metrics');
    }
};
