<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'role_id' => 4,
                'name' => 'System Administrator',
                'password' => bcrypt('password'),
                'account_status' => 'Active',
            ]
        );
    }
}
