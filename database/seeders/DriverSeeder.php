<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    public function run(): void
    {

        Driver::create([
            'license_number' => 'N01-23-456789',
            'first_name' => 'Juan',
            'middle_name' => 'Santos',
            'last_name' => 'Dela Cruz',
            'address' => 'General Trias, Cavite',
            'birth_date' => '1995-05-15',
            'contact_number' => '09171234567',
            'license_type' => 'Professional',
            'license_expiration' => '2028-05-15',
        ]);


        Driver::create([
            'license_number' => 'N02-34-567890',
            'first_name' => 'Maria',
            'middle_name' => 'Lopez',
            'last_name' => 'Santos',
            'address' => 'Dasmarinas, Cavite',
            'birth_date' => '1998-08-20',
            'contact_number' => '09181234567',
            'license_type' => 'Non-Professional',
            'license_expiration' => '2027-08-20',
        ]);


        Driver::create([
            'license_number' => 'N03-45-678901',
            'first_name' => 'Pedro',
            'middle_name' => 'Garcia',
            'last_name' => 'Reyes',
            'address' => 'Imus, Cavite',
            'birth_date' => '1992-11-10',
            'contact_number' => '09191234567',
            'license_type' => 'Professional',
            'license_expiration' => '2029-11-10',
        ]);
    }
}
