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
        Schema::create('tpas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // TPA Name
            $table->string('code')->unique(); // Unique TPA Code
            $table->string('contact_number'); // Organization Contact No
            $table->text('address')->nullable(); // Office Address

            // Contact Person Details
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();

            $table->boolean('status')->default(true); // Active/Inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpas');
    }
};
