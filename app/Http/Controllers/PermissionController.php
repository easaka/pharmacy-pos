<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Show the form for editing user permissions.
     */
    public function edit(User $user)
    {
        // Prevent editing admin permissions
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Admin users have all permissions by default.');
        }

        $allPermissions = Permission::orderBy('name')->get();
        $userPermissions = $user->getPermissions();

        return view('permissions.edit', compact('user', 'allPermissions', 'userPermissions'));
    }

    /**
     * Update user permissions.
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing admin permissions
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Admin users have all permissions by default.');
        }

        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Remove all existing permissions for this role
        DB::table('role_permissions')
            ->where('role', $user->role)
            ->delete();

        // Add selected permissions
        if (!empty($validated['permissions'])) {
            $permissions = $validated['permissions'];
            $permissionData = [];
            
            foreach ($permissions as $permissionId) {
                $permission = Permission::findOrFail($permissionId);
                $permissionData[] = [
                    'role' => $user->role,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($permissionData)) {
                DB::table('role_permissions')->insert($permissionData);
            }
        }

        // Clear cache for this user
        cache()->forget("user_{$user->id}_permissions");
        cache()->forget("user_{$user->id}_permission_*");

        return redirect()->route('users.index')
            ->with('success', 'Permissions updated successfully for ' . $user->name);
    }

    /**
     * Update permissions for a role (affects all users with that role).
     */
    public function updateRolePermissions(Request $request, string $role)
    {
        if ($role === 'admin') {
            return redirect()->route('permissions.roles')
                ->with('error', 'Admin role has all permissions by default.');
        }

        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Remove all existing permissions for this role
        DB::table('role_permissions')
            ->where('role', $role)
            ->delete();

        // Add selected permissions
        if (!empty($validated['permissions'])) {
            $permissionData = [];
            
            foreach ($validated['permissions'] as $permissionId) {
                $permissionData[] = [
                    'role' => $role,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($permissionData)) {
                DB::table('role_permissions')->insert($permissionData);
            }
        }

        // Clear cache for all users with this role
        User::where('role', $role)->each(function ($user) {
            cache()->forget("user_{$user->id}_permissions");
        });

        return redirect()->route('permissions.roles')
            ->with('success', 'Permissions updated successfully for ' . ucfirst($role) . ' role.');
    }

    /**
     * Show role permissions management.
     */
    public function roles()
    {
        $roles = ['cashier', 'pharmacist'];
        $allPermissions = Permission::orderBy('name')->get();
        
        $rolePermissions = [];
        foreach ($roles as $role) {
            $permissionIds = DB::table('role_permissions')
                ->where('role', $role)
                ->pluck('permission_id')
                ->toArray();
            $rolePermissions[$role] = $permissionIds;
        }

        return view('permissions.roles', compact('roles', 'allPermissions', 'rolePermissions'));
    }
}
