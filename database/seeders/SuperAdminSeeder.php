<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Super Admin']);

        $permissions = [
            'manage users',
            'manage roles',
            'manage permissions',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $role->syncPermissions(Permission::all());

        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'], 
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => bcrypt('p@ssw0rd'), 
            ]
        );

        $user->assignRole($role);
    }
}
