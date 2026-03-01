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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique(); // APP-24-0001

            // Core Relationships
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('charge_id')->nullable()->constrained(); // Link to Charge Master

            // Scheduling
            $table->date('date');
            $table->foreignId('global_shift_id')->constrained();
            $table->foreignId('doctor_schedule_id')->constrained(); // Link to the JSON-day schedule
            $table->time('time_slot'); // The specific time picked (e.g., 10:30 AM)

            // States (Using Enums)
            $table->integer('priority')->default(1); // AppointmentPriority Enum
            $table->string('status')->default('pending'); // AppointmentStatus Enum

            // Financials (Captured at time of booking to prevent history changes)
            $table->decimal('doctor_fees', 10, 2);
            $table->decimal('hospital_fees', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('net_amount', 10, 2); // Calculated: (Doc + Hosp) - Discount

            // Payment
            $table->foreignId('payment_method_id')->constrained();
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, partial
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('attachment')->nullable(); // Document upload path

            // Communication & Features
            $table->text('message')->nullable();
            $table->boolean('live_consult')->default(false); // Video conference toggle
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
