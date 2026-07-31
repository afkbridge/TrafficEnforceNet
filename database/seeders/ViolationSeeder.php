<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Violation;
use Carbon\Carbon;

class ViolationSeeder extends Seeder
{
    public function run(): void
    {

        Violation::create([
            'ticket_number' => 'TN-0001',
            'driver_id' => 1,
            'vehicle_id' => 1,
            'violation_type_id' => 2, // Illegal Parking
            'user_id' => 1,
            'violation_date' => Carbon::today(),
            'violation_time' => '09:30:00',
            'location' => 'General Trias City Hall',
            'latitude' => 14.3215,
            'longitude' => 120.9073,
            'remarks' => 'Vehicle parked in prohibited area',
            'status' => 'Pending',
        ]);


        Violation::create([
            'ticket_number' => 'TN-0002',
            'driver_id' => 2,
            'vehicle_id' => 2,
            'violation_type_id' => 8, // Driving Without License
            'user_id' => 1,
            'violation_date' => Carbon::today(),
            'violation_time' => '11:15:00',
            'location' => 'Governor Drive',
            'latitude' => 14.3230,
            'longitude' => 120.9050,
            'remarks' => 'Driver failed to present license',
            'status' => 'Completed',
        ]);


        Violation::create([
            'ticket_number' => 'TN-0003',
            'driver_id' => 3,
            'vehicle_id' => 3,
            'violation_type_id' => 1, // Truck Ban
            'user_id' => 1,
            'violation_date' => Carbon::now()->subDays(5),
            'violation_time' => '08:00:00',
            'location' => 'Open Canal Road',
            'latitude' => 14.3200,
            'longitude' => 120.9100,
            'remarks' => 'Truck entered restricted area',
            'status' => 'Pending',
        ]);


        Violation::create([
            'ticket_number' => 'TN-0004',
            'driver_id' => 1,
            'vehicle_id' => 1,
            'violation_type_id' => 5, // Disregarding Traffic Signs
            'user_id' => 1,
            'violation_date' => Carbon::now()->subDays(10),
            'violation_time' => '14:20:00',
            'location' => 'Malabon Road',
            'latitude' => 14.3250,
            'longitude' => 120.9120,
            'remarks' => 'Ignored traffic signal',
            'status' => 'Completed',
        ]);


        Violation::create([
            'ticket_number' => 'TN-0005',
            'driver_id' => 2,
            'vehicle_id' => 2,
            'violation_type_id' => 2, // Illegal Parking
            'user_id' => 1,
            'violation_date' => Carbon::now()->subDays(2),
            'violation_time' => '16:45:00',
            'location' => 'City Market Area',
            'latitude' => 14.3190,
            'longitude' => 120.9060,
            'remarks' => 'Blocking pedestrian lane',
            'status' => 'Pending',
        ]);
    }
}
