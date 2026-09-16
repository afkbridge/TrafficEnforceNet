<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Administrator',
                'description' => 'POSO administrator'
            ]
        );

        Role::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'POSO Enforcer',
                'description' => 'Traffic enforcement officer'
            ]
        );

        Role::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'BPLO Personnel',
                'description' => 'Business permit and licensing office personnel'
            ]
        );

        Role::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Super Administrator',
                'description' => 'System account and user management administrator'
            ]
        );
    }
}