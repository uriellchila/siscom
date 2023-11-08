<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'Admin']);

        $permission = Permission::create(['name' => 'menu.country'])->syncRoles([$role]);
        $permission = Permission::create(['name' => 'menu.state'])->syncRoles([$role]);
        $permission = Permission::create(['name' => 'menu.city'])->syncRoles([$role]);
    }
}
