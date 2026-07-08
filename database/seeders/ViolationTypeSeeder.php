<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ViolationType;

class ViolationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $violations = [

            [
                'name' => 'Truck Ban',
                'description' => 'Violation of Truck Ban Ordinance'
            ],

            [
                'name' => 'Illegal Parking',
                'description' => 'Vehicle parked in a prohibited area'
            ],

            [
                'name' => 'Obstruction',
                'description' => 'Causing obstruction to traffic'
            ],

            [
                'name' => 'Stalled Vehicle',
                'description' => 'Improper handling of stalled vehicle'
            ],

            [
                'name' => 'Disregarding Traffic Signs',
                'description' => 'Failure to obey traffic signs or signals'
            ],

            [
                'name' => 'Reckless Driving',
                'description' => 'Driving in a reckless manner'
            ],

            [
                'name' => 'Driving Under the Influence',
                'description' => 'Driving while under the influence of alcohol or drugs'
            ],

            [
                'name' => 'Driving Without License',
                'description' => 'Operating a vehicle without a valid license'
            ],

            [
                'name' => 'Unregistered Vehicle',
                'description' => 'Vehicle registration has expired or is unavailable'
            ],

            [
                'name' => 'Counterflow',
                'description' => 'Driving against the flow of traffic'
            ],

            [
                'name' => 'Overloading',
                'description' => 'Vehicle exceeds allowable passenger or cargo capacity'
            ],

            [
                'name' => 'Involvement in Accident',
                'description' => 'Vehicle involved in a traffic accident'
            ],

            [
                'name' => 'Loading / Unloading in Prohibited Area',
                'description' => 'Loading or unloading passengers/cargo in prohibited zones'
            ],

            [
                'name' => 'Coding Violation',
                'description' => 'Violation of number coding scheme'
            ],

            [
                'name' => 'Colorum',
                'description' => 'Unauthorized public utility vehicle'
            ],

            [
                'name' => 'Others',
                'description' => 'Other traffic violations'
            ],

        ];

        foreach ($violations as $violation) {
            ViolationType::create($violation);
        }
    }
}