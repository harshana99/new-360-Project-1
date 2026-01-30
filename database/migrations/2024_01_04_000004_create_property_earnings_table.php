<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Owner
            $table->decimal('earnings_from_investments', 18, 2)->default(0);
            $table->decimal('earnings_from_rentals', 18, 2)->default(0);
            $table->decimal('total_earnings', 18, 2)->default(0);
            $table->date('month');
            $table->timestamps();

            $table->index(['property_id', 'user_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_earnings');
    }
};
