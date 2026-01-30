<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop the incorrect foreign key (referencing 'admins')
            // Note: The constraint name is usually 'properties_admin_approved_by_foreign' based on error log
            $table->dropForeign('properties_admin_approved_by_foreign');
            
            // Re-add the foreign key referencing 'users' table (where admin logins actually live)
            $table->foreign('admin_approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['admin_approved_by']);
            // Restore original (broken) constraint if rolled back
            $table->foreign('admin_approved_by')->references('id')->on('admins')->onDelete('set null');
        });
    }
};
