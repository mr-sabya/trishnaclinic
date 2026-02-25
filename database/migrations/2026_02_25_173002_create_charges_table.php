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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charge_category_id')->constrained();
            $table->foreignId('tax_category_id')->constrained();
            $table->foreignId('unit_id')->nullable()->constrained();
            $table->string('name'); // e.g., Oxygen, MRI Brain
            $table->string('code')->unique();
            $table->decimal('standard_charge', 15, 2); // Price for cash patients
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};
