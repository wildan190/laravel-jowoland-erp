<?php

namespace App\Http\Controllers\RBAC;

use App\Action\RBAC\RoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RBAC\RoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $roleAction;

    public function __construct(RoleAction $roleAction)
    {
        $this->roleAction = $roleAction;
    }

    // Tampilkan halaman role
    public function index()
    {
        $roles = $this->roleAction->getAllRoles();
        $permissions = Permission::all();
        $users = User::all();

        return view('rbac.role', compact('roles', 'permissions', 'users'));
    }

    // Tambah role baru
    public function store(RoleRequest $request)
    {
        $this->roleAction->createRole($request->validated());

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat');
    }

    // Update permissions → hanya memanggil Action
    public function updatePermissions(Request $request, Role $role)
    {
        $permissions = $request->input('permissions', []);
        $this->roleAction->updateRolePermissions($role, $permissions);

        return redirect()->route('roles.index')->with('success', 'Permissions berhasil diperbarui');
    }

    // Assign users → hanya memanggil Action
    public function assignUsers(Request $request, Role $role)
    {
        $userIds = $request->input('users', []);
        $this->roleAction->updateRoleUsers($role, $userIds);

        return redirect()->route('roles.index')->with('success', 'Users berhasil diassign');
    }

    // Hapus role
    public function destroy(Role $role)
    {
        $this->roleAction->deleteRole($role);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus');
    }
}
