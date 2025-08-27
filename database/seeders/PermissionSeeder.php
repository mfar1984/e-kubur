<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions based on the new table structure
        $permissions = [
            // Paparan Pemuka
            'view overview',
            'edit overview',
            'delete overview',
            
            // Daftar Kematian
            'create kematian',
            'view kematian',
            'edit kematian',
            'delete kematian',
            
            // Ahli PPJUB
            'create ppjub',
            'view ppjub',
            'edit ppjub',
            'delete ppjub',
            
            // Tetapan Umum
            'create settings',
            'view settings',
            'edit settings',
            'delete settings',
            
            // Kumpulan Akses
            'create roles',
            'view roles',
            'edit roles',
            'delete roles',
            
            // Pengguna Akses
            'create users',
            'view users',
            'edit users',
            'delete users',
            
            // Integrasi
            'create integration',
            'view integration',
            'edit integration',
            'delete integration',
            
            // Log Audit & Keselamatan
            'create audit',
            'view audit',
            'edit audit',
            'delete audit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create basic roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'Pengguna']);
        $moderatorRole = Role::firstOrCreate(['name' => 'Moderator']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());

        // Assign basic permissions to user
        $userRole->givePermissionTo([
            'view overview',
            'view kematian',
            'view ppjub',
        ]);

        // Assign moderate permissions to moderator
        $moderatorRole->givePermissionTo([
            'view overview',
            'view kematian',
            'create kematian',
            'edit kematian',
            'view ppjub',
            'create ppjub',
            'edit ppjub',
            'view users',
            'view roles',
            'view settings',
            'view audit',
        ]);
    }
}
