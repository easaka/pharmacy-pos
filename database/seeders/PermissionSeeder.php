<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'View Products', 'slug' => 'view-products', 'description' => 'View product listings'],
            ['name' => 'Manage Products', 'slug' => 'manage-products', 'description' => 'Create, edit, and delete products'],
            ['name' => 'Manage Stock', 'slug' => 'manage-stock', 'description' => 'Add stock entries and manage inventory'],
            ['name' => 'Process Sales', 'slug' => 'process-sales', 'description' => 'Process POS transactions and sales'],
            ['name' => 'View Reports', 'slug' => 'view-reports', 'description' => 'View sales and inventory reports'],
            ['name' => 'Export Reports', 'slug' => 'export-reports', 'description' => 'Export reports to PDF/Excel'],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'description' => 'Create, edit, and delete users'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'description' => 'Assign permissions to roles'],
            ['name' => 'Manage Categories', 'slug' => 'manage-categories', 'description' => 'Create, edit, and delete product categories'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign default permissions to roles
        // By default, non-admin users only get: view products and POS access
        $defaultUserPermissions = ['view-products', 'process-sales'];
        
        // Cashier: default permissions only
        $cashierPermissions = $defaultUserPermissions;
        $this->assignPermissionsToRole('cashier', $cashierPermissions);

        // Pharmacist: default permissions only (admin can grant more later)
        $pharmacistPermissions = $defaultUserPermissions;
        $this->assignPermissionsToRole('pharmacist', $pharmacistPermissions);

        // Admin has all permissions automatically (handled in User model)
    }

    private function assignPermissionsToRole(string $role, array $permissionSlugs): void
    {
        foreach ($permissionSlugs as $slug) {
            $permission = Permission::where('slug', $slug)->first();
            if ($permission) {
                DB::table('role_permissions')->updateOrInsert(
                    [
                        'role' => $role,
                        'permission_id' => $permission->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
