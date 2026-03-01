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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            // Personal Info
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('gender')->nullable();
            $table->string('photo')->nullable();
            $table->text('address')->nullable();

            // Professional Info
            $table->foreignId('medical_department_id')->constrained('medical_departments')->onDelete('cascade');
            $table->foreignId('specialist_id')->constrained('specialists')->onDelete('cascade');
            $table->string('designation');
            $table->string('qualification');
            $table->string('experience')->nullable();

            // Financial Split: Appointment
            $table->decimal('appointment_doctor_fee', 10, 2)->default(0);
            $table->decimal('appointment_hospital_fee', 10, 2)->default(0);

            // Financial Split: OPD (Out-Patient)
            $table->decimal('opd_doctor_fee', 10, 2)->default(0);
            $table->decimal('opd_hospital_fee', 10, 2)->default(0);

            // Financial Split: IPD (In-Patient / Admission)
            $table->decimal('ipd_doctor_fee', 10, 2)->default(0);
            $table->decimal('ipd_hospital_fee', 10, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
