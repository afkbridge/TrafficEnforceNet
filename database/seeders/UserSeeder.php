<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Enforcer;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Administrator Account
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['email' => 'admin@trafficenforcenet.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'account_status' => 'Active',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | BPLO Account
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['email' => 'bplo@trafficenforcenet.com'],
            [
                'name' => 'BPLO Personnel',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'account_status' => 'Active',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Enforcer Account
        |--------------------------------------------------------------------------
        */

        $enforcerUser = User::updateOrCreate(
            ['email' => 'enforcer@trafficenforcenet.com'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'account_status' => 'Active',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Enforcer Profile
        |--------------------------------------------------------------------------
        */

        Enforcer::updateOrCreate(
            ['user_id' => $enforcerUser->id],
            [
                'badge_number' => 'POSO-001',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'position' => 'Traffic Enforcer I',
                'employment_status' => 'Active',
            ]
        );
    }
}