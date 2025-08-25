<?php

namespace App\Action\RBAC;

use Spatie\Permission\Models\Permission;

class PermissionAction
{
    public function getAll()
    {
        return Permission::all();
    }

    public function store(array $data)
    {
        return Permission::create(['name' => $data['name']]);
    }

    public function update(Permission $permission, array $data)
    {
        $permission->update(['name' => $data['name']]);

        return $permission;
    }

    public function delete(Permission $permission)
    {
        return $permission->delete();
    }
}
