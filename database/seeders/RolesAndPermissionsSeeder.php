<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definisi permissions
        $permissions = [
            // User management
            'user.view', 'user.create', 'user.edit', 'user.delete',
            // Role management
            'role.view', 'role.create', 'role.edit', 'role.delete',
            // Permission management
            'permission.view', 'permission.create', 'permission.edit', 'permission.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Admin dapat semua kecuali permission management
        $admin->syncPermissions([
            'user.view', 'user.create', 'user.edit', 'user.delete',
            'role.view',
        ]);

        // Super admin dapat semua (via gate in AuthServiceProvider)
        // User biasa tidak dapat permission apapun

        // Buat user super-admin default
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdminUser->assignRole($superAdmin);
    }
}