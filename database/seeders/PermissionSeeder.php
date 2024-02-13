<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $rolesList = Role::all()->keyBy('id');

        $permissions = [
            'properties-manage' => [Role::ROLE_OWNER],
            'bookings-manage' => [Role::ROLE_USER]
        ];

        foreach ($permissions as $permissionName => $rolesIds) {
            $permission = Permission::create([
                'name' => $permissionName,
            ]);
            foreach ($rolesIds as $roleId) {
                $rolesList[$roleId]->permissions()->attach($permission);
            }
        }
    }
}
