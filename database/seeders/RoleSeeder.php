<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // STAFF
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions([
            'stock in',
            'stock out',
        ]);

        // FINANCE
        $finance = Role::firstOrCreate(['name' => 'finance']);
        $finance->syncPermissions([
            'view report',
            'export report',
        ]);
    }
}
