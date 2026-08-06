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

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@trafficenforcenet.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'account_status' => 'Active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | BPLO Account
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'BPLO Personnel',
            'email' => 'bplo@trafficenforcenet.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
            'account_status' => 'Active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Enforcer Account
        |--------------------------------------------------------------------------
        */

        $enforcerUser = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'enforcer@trafficenforcenet.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'account_status' => 'Active',
        ]);


        Enforcer::create([
            'user_id' => $enforcerUser->id,
            'badge_number' => 'POSO-001',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'position' => 'Traffic Enforcer I',
            'employment_status' => 'Active',
        ]);
    }
}