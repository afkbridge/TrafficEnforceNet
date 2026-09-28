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
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Brie',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'account_status' => 'Active',
            ]
        );

        User::updateOrCreate(
            ['username' => 'bplo'],
            [
                'name' => 'BPLO Personnel',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'account_status' => 'Active',
            ]
        );

        $enforcerUser = User::updateOrCreate(
            ['username' => 'enforcer'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'account_status' => 'Active',
            ]
        );

        Enforcer::updateOrCreate(
            ['user_id' => $enforcerUser->id],
            [
                'badge_number' => 'POSO-001',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'position' => 'Traffic Enforcer',
                'employment_status' => 'Active',
            ]
        );
    }
}
