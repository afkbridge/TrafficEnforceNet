<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {

        Vehicle::create([
            'driver_id' => 1,
            'plate_number' => 'ABC-1234',
            'vehicle_type' => 'Sedan',
            'brand' => 'Toyota',
            'model' => 'Vios',
            'color' => 'White',
            'engine_number' => 'ENG123456',
            'chassis_number' => 'CHS123456',
        ]);


        Vehicle::create([
            'driver_id' => 2,
            'plate_number' => 'XYZ-5678',
            'vehicle_type' => 'Motorcycle',
            'brand' => 'Honda',
            'model' => 'Click 125',
            'color' => 'Red',
            'engine_number' => 'ENG567890',
            'chassis_number' => 'CHS567890',
        ]);


        Vehicle::create([
            'driver_id' => 3,
            'plate_number' => 'DEF-9012',
            'vehicle_type' => 'Truck',
            'brand' => 'Isuzu',
            'model' => 'Elf',
            'color' => 'Blue',
            'engine_number' => 'ENG901234',
            'chassis_number' => 'CHS901234',
        ]);
    }
}
