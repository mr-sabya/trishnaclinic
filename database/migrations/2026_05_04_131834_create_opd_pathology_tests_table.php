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
        Schema::create('opd_pathology_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opd_admission_id')->constrained()->onDelete('cascade');
            $table->foreignId('pathology_test_id')->constrained();
            $table->datetime('test_date');
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->text('instruction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_pathology_tests');
    }
};
