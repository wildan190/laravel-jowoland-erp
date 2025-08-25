<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // relasi user yang diassign ke role
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id')->wherePivot('model_type', User::class);
    }

    // update permissions (production-ready)
    public function updatePermissions(array $permissions)
    {
        $this->permissions()->detach();
        if (! empty($permissions)) {
            $this->givePermissionTo($permissions);
        }
    }

    // assign users (production-ready)
    public function updateAssignedUsers(array $userIds)
    {
        $this->assignedUsers()->detach();
        if (! empty($userIds)) {
            $this->assignedUsers()->attach($userIds);
        }
    }
}
