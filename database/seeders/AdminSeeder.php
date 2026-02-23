<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use updateOrCreate to prevent duplicates if you run the seeder twice
        User::updateOrCreate(
            ['phone' => '01929190241'], // Check by unique phone
            [
                'name'      => 'Super Admin',
                'email'     => 'admin@hospital.com',
                'password'  => Hash::make('password'), // Change this for production!
                'role'      => UserRole::SUPER_ADMIN,
                'is_active' => true,
            ]
        );

        $this->command->info('Super Admin created successfully.');
        $this->command->info('Phone: 01929190241');
        $this->command->info('Password: password');
    }
}
