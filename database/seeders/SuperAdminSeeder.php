<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@trafficenforcenet.com'],
            [
                'role_id' => 4,
                'name' => 'System Administrator',
                'password' => bcrypt('password'),
                'account_status' => 'active',
            ]
        );
    }
}