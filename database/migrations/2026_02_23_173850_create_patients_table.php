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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            // Link to User table for login (Phone/Password)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Link to TPA/Insurance table
            $table->foreignId('tpa_id')->nullable()->constrained('tpas')->onDelete('set null');

            // Unique Hospital ID (e.g., PAT-2024-0001)
            $table->string('mrn_number')->unique();

            // Personal Info
            $table->string('guardian_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender'); // Enum: Male, Female, Other
            $table->string('blood_group')->nullable(); // Enum: A+, O-, etc.
            $table->string('marital_status')->nullable(); // Enum: Single, Married, etc.
            $table->string('photo')->nullable();

            // Identity (NID/Birth Certificate for Bangladesh)
            $table->string('identification_number')->nullable();

            // Contact (Email & Phone usually come from User, but stored here for fast access)
            $table->text('address')->nullable();

            // Medical Info
            $table->text('known_allergies')->nullable();
            $table->text('remarks')->nullable();

            // Insurance/TPA details
            $table->string('insurance_id')->nullable(); // Policy Number
            $table->date('tpa_validity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
