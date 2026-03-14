<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('opd_admissions', function (Blueprint $table) {
            // Financial tracking columns
            $table->decimal('doctor_fee', 15, 2)->default(0)->after('status');
            $table->decimal('hospital_fee', 15, 2)->default(0)->after('doctor_fee');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('hospital_fee');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('discount_percentage');
            $table->decimal('net_amount', 15, 2)->default(0)->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('opd_admissions', function (Blueprint $table) {
            $table->dropColumn(['doctor_fee', 'hospital_fee', 'discount_percentage', 'discount_amount', 'net_amount']);
        });
    }
};
