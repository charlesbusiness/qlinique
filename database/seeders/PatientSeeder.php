<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\PatientFile;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $file1 = PatientFile::create([
            'name' => 'Alice Kamau',
            'email' => 'alice@example.com',
            'phone' => '0711000001',
            'address' => '123 Main Street, Nairobi',
            'type' => 'individual',
        ]);

        $file2 = PatientFile::create([
            'name' => 'Grace Otieno',
            'email' => 'grace@example.com',
            'phone' => '0722000001',
            'address' => '456 Park Road, Mombasa',
            'type' => 'individual',
        ]);

        $file3 = PatientFile::create([
            'name' => 'Samuel Kiprop',
            'email' => 'samuel@example.com',
            'phone' => '0733000001',
            'address' => '789 Valley View, Kisumu',
            'type' => 'individual',
        ]);

        Patient::create([
            'file_id' => $file1->id,
            'name' => 'Alice Kamau',
            'gender' => 'female',
            'date_of_birth' => '1990-05-15',
            'phone' => '0711000001',
            'address' => '123 Main Street, Nairobi',
            'religion' => 'Christianity',
            'next_of_kin' => [
                'name' => 'Bob Kamau',
                'relationship' => 'Spouse',
                'phone' => '0711000002',
            ],
        ]);

        Patient::create([
            'file_id' => $file2->id,
            'name' => 'Grace Otieno',
            'gender' => 'female',
            'date_of_birth' => '1985-10-20',
            'phone' => '0722000001',
            'address' => '456 Park Road, Mombasa',
            'religion' => 'Christianity',
        ]);

        Patient::create([
            'file_id' => $file3->id,
            'name' => 'Samuel Kiprop',
            'gender' => 'male',
            'date_of_birth' => '1978-03-08',
            'phone' => '0733000001',
            'address' => '789 Valley View, Kisumu',
        ]);
    }
}
