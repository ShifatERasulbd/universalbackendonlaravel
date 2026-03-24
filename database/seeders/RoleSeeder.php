<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $allPermissions = Permission::pluck('name')->all();

        $roles = [
            'super-admin' => $allPermissions,
            'editor' => [
                'dashboard.view',
                'users.view',
                'users.create',
                'users.edit',
                'users.assign-roles',
                'menus.view',
                'menus.create',
                'menus.edit',
                'menus.delete',
                'pages.view',
                'pages.create',
                'pages.edit',
                'pages.delete',
                'available-websites.view',
                'available-websites.create',
                'available-websites.edit',
                'available-websites.delete',
                'templates.view',
                'templates.create',
                'templates.edit',
                'templates.delete',
            ],
            'reporter' => [
                'dashboard.view',
            ],
            'moderator' => [
                'dashboard.view',
                'users.view',
                'users.edit',
            ],
            'subscriber' => [
                'dashboard.view',
            ],
        ];

        foreach ($roles as $name => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
