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
        Schema::create('radiology_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->string('short_name');
            $table->string('test_type')->nullable();
            $table->foreignId('radiology_category_id')->constrained('radiology_categories')->onDelete('cascade');
            $table->string('sub_category')->nullable();
            $table->string('method')->nullable();
            $table->integer('report_days')->default(0);
            // Linked to the Charge Master table
            $table->foreignId('charge_id')->constrained('charges')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('radiology_test_parameter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_test_id')->constrained('radiology_tests')->onDelete('cascade');
            $table->foreignId('radiology_parameter_id')->constrained('radiology_parameters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_tests');
    }
};
