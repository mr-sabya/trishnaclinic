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
        Schema::create('opd_admission_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opd_admission_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained(); // Linked to your PaymentMethod model
            $table->decimal('paid_amount', 15, 2);
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('document')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_admission_payments');
    }
};
