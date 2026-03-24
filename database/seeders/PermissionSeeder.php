<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}