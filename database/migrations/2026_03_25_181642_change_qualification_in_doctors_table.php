<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Update the field (e.g., make it nullable and change length to 500)
            $table->string('qualification', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Revert the field to its original state (e.g., non-nullable, default length)
            $table->string('qualification', 255)->nullable(false)->change();
        });
    }
};
