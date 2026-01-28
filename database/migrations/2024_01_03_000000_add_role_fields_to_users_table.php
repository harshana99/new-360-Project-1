<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add role and admin fields to users table
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add is_admin flag
            $table->boolean('is_admin')->default(false)->after('status');
            
            // Add role field (for future expansion)
            $table->string('role')->nullable()->after('is_admin');
            
            // Add indexes
            $table->index('is_admin');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_admin']);
            $table->dropIndex(['role']);
            $table->dropColumn(['is_admin', 'role']);
        });
    }
};
