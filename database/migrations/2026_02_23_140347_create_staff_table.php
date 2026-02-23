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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_department_id')->nullable()->constrained()->onDelete('set null');

            // BD Security Fields
            $table->string('employee_id')->unique();
            $table->string('nid_number')->unique();
            $table->string('father_name');
            $table->string('mother_name');

            // Personal & Address
            $table->string('gender');
            $table->string('blood_group')->nullable();
            $table->date('date_of_birth');
            $table->text('present_address');
            $table->text('permanent_address');

            // Employment
            $table->string('designation');
            $table->date('joining_date');
            $table->decimal('salary', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
