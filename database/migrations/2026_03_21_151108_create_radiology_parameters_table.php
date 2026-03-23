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
        Schema::create('radiology_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('parameter_name');
            $table->string('reference_range_from')->nullable();
            $table->string('reference_range_to')->nullable();
            $table->foreignId('radiology_unit_id')->constrained('radiology_units')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_parameters');
    }
};
