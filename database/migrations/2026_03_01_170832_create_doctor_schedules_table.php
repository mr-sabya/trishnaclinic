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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('global_shift_id')->constrained()->onDelete('cascade');

            // Store days as JSON: ["Monday", "Tuesday"]
            $table->json('available_days');

            $table->time('start_time');
            $table->time('end_time');
            $table->integer('avg_consultation_time')->default(15); // in minutes
            $table->integer('max_appointments')->default(20);

            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
