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
        Role::create([
            'name' => 'Administrator',
            'description' => 'System administrator'
        ]);

        Role::create([
            'name' => 'POSO Enforcer',
            'description' => 'Traffic enforcement officer'
        ]);

        Role::create([
            'name' => 'BPLO Personnel',
            'description' => 'Business permit and licensing office personnel'
        ]);
    }
}