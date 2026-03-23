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
        Schema::create('ipd_admission_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipd_admission_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained();
            $table->decimal('paid_amount', 12, 2);
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('document')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_admission_payments');
    }
};
