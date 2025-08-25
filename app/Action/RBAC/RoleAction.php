<?php

namespace App\Action\RBAC;

use App\Models\Role;

class RoleAction
{
    // Ambil semua role beserta permission dan assigned user
    public function getAllRoles()
    {
        return Role::with('permissions', 'assignedUsers')->get();
    }

    // Buat role baru
    public function createRole(array $data)
    {
        $role = new Role;
        $role->name = $data['name'];
        $role->save();

        if (! empty($data['permissions'])) {
            $role->updatePermissions($data['permissions']);
        }

        return $role;
    }

    // Update permissions role
    public function updateRolePermissions(Role $role, array $permissions)
    {
        $role->updatePermissions($permissions);

        return $role;
    }

    // Assign users ke role
    public function updateRoleUsers(Role $role, array $userIds)
    {
        // detach semua dulu
        $role->assignedUsers()->detach();

        // attach dengan tambahan pivot model_type
        foreach ($userIds as $userId) {
            $role->assignedUsers()->attach($userId, ['model_type' => \App\Models\User::class]);
        }

        return $role;
    }

    // Hapus role
    public function deleteRole(Role $role)
    {
        $role->delete();
    }
}
