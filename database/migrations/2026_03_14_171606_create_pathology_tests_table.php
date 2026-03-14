<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Main Pathology Test Table
        Schema::create('pathology_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->string('short_name');
            $table->string('test_type')->nullable(); // From your HTML

            // Category Link
            $table->foreignId('pathology_category_id')->constrained()->onDelete('cascade');

            $table->string('sub_category')->nullable(); // From your HTML
            $table->string('method')->nullable();       // From your HTML
            $table->integer('report_days')->default(0); // From your HTML

            // Financial Link (The "Code" dropdown in your HTML)
            // This links to your existing 'charges' table
            $table->foreignId('charge_id')->constrained('charges')->onDelete('cascade');

            $table->timestamps();
        });

        // 2. Pivot Table for Parameters (The dynamic table at the bottom of your HTML)
        // This allows one test (like CBC) to have many parameters (Hb, WBC, RBC)
        Schema::create('pathology_test_parameter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pathology_test_id')->constrained()->onDelete('cascade');
            $table->foreignId('pathology_parameter_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathology_test_parameter');
        Schema::dropIfExists('pathology_tests');
    }
};
