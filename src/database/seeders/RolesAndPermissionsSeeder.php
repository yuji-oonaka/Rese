<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [
            'admin' => ['manage users', 'manage shops'],
            'representative' => ['edit shop', 'view reservations'],
            'user' => ['make reservations']
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName]);
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
                $role->givePermissionTo($permission);
            }
        }
    }
}
