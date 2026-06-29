<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        Patient::create([
            'file_number' => 'FAC-2026-00001',
            'name' => 'Alice Kamau',
            'gender' => 'female',
            'date_of_birth' => '1990-05-15',
            'phone' => '0711000001',
            'address' => '123 Main Street, Nairobi',
            'account_type' => 'individual',
            'religion' => 'Christianity',
            'next_of_kin' => [
                'name' => 'Bob Kamau',
                'relationship' => 'Spouse',
                'phone' => '0711000002',
            ],
        ]);

        Patient::create([
            'file_number' => 'FAC-2026-00002',
            'name' => 'Grace Otieno',
            'gender' => 'female',
            'date_of_birth' => '1985-10-20',
            'phone' => '0722000001',
            'address' => '456 Park Road, Mombasa',
            'account_type' => 'individual',
            'religion' => 'Christianity',
        ]);

        Patient::create([
            'file_number' => 'FAC-2026-00003',
            'name' => 'Samuel Kiprop',
            'gender' => 'male',
            'date_of_birth' => '1978-03-08',
            'phone' => '0733000001',
            'address' => '789 Valley View, Kisumu',
            'account_type' => 'individual',
        ]);
    }
}
