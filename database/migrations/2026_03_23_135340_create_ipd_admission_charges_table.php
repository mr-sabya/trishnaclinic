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
        Schema::create('ipd_admission_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipd_admission_id')->constrained()->onDelete('cascade');
            $table->foreignId('charge_id')->constrained();
            $table->decimal('standard_charge', 12, 2);
            $table->decimal('applied_charge', 12, 2);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_admission_charges');
    }
};
