<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'branches.view', 'branches.create', 'branches.edit', 'branches.delete',
            'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
            'loans.view', 'loans.create', 'loans.edit', 'loans.delete',
            'loans.approve', 'loans.release',
            'loan-products.view', 'loan-products.create', 'loan-products.edit', 'loan-products.delete',
            'savings.view', 'savings.create', 'savings.edit', 'savings.delete',
            'collections.view', 'collections.create', 'collections.edit', 'collections.delete',
            'cashiering.view', 'cashiering.create', 'cashiering.edit',
            'accounting.view', 'accounting.create', 'accounting.edit',
            'reports.view',
            'settings.view', 'settings.edit',
            'audit.view',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        $branchManager = Role::create(['name' => 'Branch Manager', 'guard_name' => 'web']);
        $branchManager->givePermissionTo([
            'users.view',
            'clients.view', 'clients.create', 'clients.edit',
            'loans.view', 'loans.create', 'loans.edit', 'loans.approve', 'loans.release',
            'loan-products.view',
            'savings.view', 'savings.create', 'savings.edit',
            'collections.view', 'collections.create', 'collections.edit',
            'cashiering.view',
            'reports.view',
        ]);

        $loanOfficer = Role::create(['name' => 'Loan Officer', 'guard_name' => 'web']);
        $loanOfficer->givePermissionTo([
            'clients.view', 'clients.create', 'clients.edit',
            'loans.view', 'loans.create', 'loans.edit',
            'loan-products.view',
            'savings.view',
            'collections.view', 'collections.create',
            'reports.view',
        ]);

        $collector = Role::create(['name' => 'Collector', 'guard_name' => 'web']);
        $collector->givePermissionTo([
            'clients.view',
            'loans.view',
            'collections.view', 'collections.create', 'collections.edit',
        ]);

        $cashier = Role::create(['name' => 'Cashier', 'guard_name' => 'web']);
        $cashier->givePermissionTo([
            'clients.view',
            'loans.view',
            'collections.view', 'collections.create',
            'cashiering.view', 'cashiering.create', 'cashiering.edit',
        ]);

        $accountant = Role::create(['name' => 'Accountant', 'guard_name' => 'web']);
        $accountant->givePermissionTo([
            'clients.view',
            'loans.view',
            'accounting.view', 'accounting.create', 'accounting.edit',
            'reports.view',
        ]);

        $auditor = Role::create(['name' => 'Auditor', 'guard_name' => 'web']);
        $auditor->givePermissionTo([
            'users.view',
            'branches.view',
            'clients.view',
            'loans.view',
            'savings.view',
            'collections.view',
            'accounting.view',
            'reports.view',
            'audit.view',
        ]);
    }
}
