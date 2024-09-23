<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use ProjectManagement\Models\User; // Adjust the namespace if necessary
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {

        $user = User::create([
            'name' => 'Ali Raza',
            'email' => 'ali.raza@gmail.com',
            'password' => Hash::make(12345678), // Set a default password; adjust as needed
        ]);

        // Define permissions with underscores
        $permissions = [
            'view_clients',
            'manage_clients',
            'view_projects',
            'manage_projects',
            'view_invoices',
            'manage_invoices',
            'view_expenses',
            'manage_expenses',
            'view_credit_debit_entries',
            'manage_credit_debit_entries',
            'view_tasks',
            'manage_tasks',
            'view_reports',
            'manage_alerts_and_notifications',
            'manage_user_roles_and_access',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'api']);
        $guestRole = Role::create(['name' => 'Guest', 'guard_name' => 'api']);
        $adminRole->givePermissionTo(Permission::all());

        $user->guard(['web' , 'api'])->assignRole('Admin');
    }
}
