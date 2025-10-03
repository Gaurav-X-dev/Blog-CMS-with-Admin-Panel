<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Define general-purpose permissions
        $permissions = [
            'manage users',
            'manage roles',
            'manage categories',
            'access settings',
            'access core',
            'manage permissions',
            'edit setting',
            'manage page',
            'add category',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define roles
        $roles = [
            'Super Admin',
            'Admin',
            'Manager',
            'Staff',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        Role::findByName('Super Admin')->syncPermissions(Permission::all());

        Role::findByName('Admin')->syncPermissions([
            'manage users',

        ]);
       
    }
}


