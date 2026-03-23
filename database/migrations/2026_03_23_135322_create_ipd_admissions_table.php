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
        Schema::create('ipd_admissions', function (Blueprint $table) {
            $table->id();
            $table->string('ipd_number')->unique();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('doctor_id')->constrained();
            $table->foreignId('bed_id')->constrained(); // Bed Assignment
            $table->datetime('admission_date');
            $table->datetime('discharge_date')->nullable();
            $table->string('case_type')->nullable();
            $table->boolean('is_casualty')->default(false);
            $table->string('refference')->nullable();
            $table->text('symptoms_description')->nullable();
            $table->text('known_allergies')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('admitted'); // admitted, discharged, cancelled

            // Financials
            $table->decimal('doctor_fee', 12, 2)->default(0);
            $table->decimal('hospital_fee', 12, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_admissions');
    }
};
