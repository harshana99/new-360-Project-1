<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->enum('document_type', ['deed', 'certificate', 'agreement', 'license', 'survey', 'other']);
            $table->string('file_url');
            $table->string('file_name');
            $table->timestamps();

            $table->index('property_id');
            $table->index('document_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_documents');
    }
};
