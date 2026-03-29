<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add user_id and type columns ONLY if they don't exist
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('doctors', 'type')) {
                $table->enum('type', ['permanent', 'on_call'])->default('permanent')->after('is_active');
            }
        });

        // 2. Transfer existing data to Users table
        // Only target doctors who don't have a user_id assigned yet
        $doctors = DB::table('doctors')->whereNull('user_id')->get();

        foreach ($doctors as $doctor) {
            // Check if name/phone/email columns still exist before trying to read them
            if (isset($doctor->name)) {
                $userId = DB::table('users')->insertGetId([
                    'name' => $doctor->name,
                    'email' => $doctor->email ?? 'doctor' . $doctor->id . '@hospital.com',
                    'phone' => $doctor->phone,
                    'password' => Hash::make($doctor->phone),
                    'role' => UserRole::DOCTOR->value,
                    'is_active' => $doctor->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('doctors')->where('id', $doctor->id)->update(['user_id' => $userId]);
            }
        }

        // 3. Cleanup: Remove old columns ONLY if they exist
        Schema::table('doctors', function (Blueprint $table) {
            $oldColumns = ['name', 'phone', 'email'];
            foreach ($oldColumns as $column) {
                if (Schema::hasColumn('doctors', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Make user_id required now that data is moved
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'name')) {
                $table->string('name')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
            }

            // Drop foreign key first if it exists
            if (Schema::hasColumn('doctors', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('doctors', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
