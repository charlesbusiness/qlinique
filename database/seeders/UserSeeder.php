<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create the super admin via: php artisan make:super-user
        // This seeder only creates test accounts for local development.

        User::create([
            'name' => 'Dr. John Doe',
            'email' => 'doctor@clinic.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'staff_id' => 'STAFF-002',
            'phone' => '0700000002',
        ]);

        User::create([
            'name' => 'Nurse Jane',
            'email' => 'nurse@clinic.com',
            'password' => Hash::make('password'),
            'role' => 'nurse',
            'staff_id' => 'STAFF-003',
            'phone' => '0700000003',
        ]);
    }
}
