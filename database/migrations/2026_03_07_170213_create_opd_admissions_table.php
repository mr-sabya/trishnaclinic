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
        Schema::create('opd_admissions', function (Blueprint $table) {
            $table->id();
            $table->string('opd_number')->unique(); // Format: OPDN-24-0001
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('doctor_id')->constrained();

            // Admission Details
            $table->datetime('appointment_date');
            $table->string('case_type')->nullable(); // New Case, Old Case
            $table->boolean('is_casualty')->default(false);
            $table->boolean('is_old_patient')->default(false);
            $table->string('refference')->nullable();

            // Clinical Data
            $table->text('symptoms_description')->nullable();
            $table->text('known_allergies')->nullable();
            $table->text('note')->nullable();

            // Status
            $table->string('status')->default('ongoing'); // ongoing, discharged
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_admissions');
    }
};
