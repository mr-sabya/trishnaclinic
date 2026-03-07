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
        Schema::create('opd_admission_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opd_admission_id')->constrained()->onDelete('cascade');
            $table->foreignId('charge_id')->constrained(); // Link to Charge Master

            // Financial Snapshot
            $table->decimal('standard_charge', 15, 2);
            $table->decimal('tpa_charge', 15, 2)->default(0);
            $table->decimal('applied_charge', 15, 2);

            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);

            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);

            $table->decimal('net_amount', 15, 2); // Calculated final amount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_admission_charges');
    }
};
