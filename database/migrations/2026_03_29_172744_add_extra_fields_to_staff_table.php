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
        Schema::table('staff', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('user_id');
            $table->text('qualification')->nullable()->after('designation');
            $table->json('documents')->nullable()->after('salary'); // Stores multiple file paths
            $table->text('remarks')->nullable()->after('documents');
            $table->boolean('is_active')->default(true)->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['photo', 'qualification', 'documents', 'remarks', 'is_active']);
        });
    }
};
