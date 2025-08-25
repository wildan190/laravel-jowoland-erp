<?php

namespace App\Http\Controllers\RBAC;

use App\Action\RBAC\PermissionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RBAC\PermissionRequest;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $permissionAction;

    public function __construct(PermissionAction $permissionAction)
    {
        $this->permissionAction = $permissionAction;
    }

    public function index()
    {
        $permissions = $this->permissionAction->getAll();

        return view('rbac.permission', compact('permissions'));
    }

    public function store(PermissionRequest $request)
    {
        $this->permissionAction->store($request->validated());

        return back()->with('success', 'Permission berhasil ditambahkan');
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $this->permissionAction->update($permission, $request->validated());

        return back()->with('success', 'Permission berhasil diperbarui');
    }

    public function destroy(Permission $permission)
    {
        $this->permissionAction->delete($permission);

        return back()->with('success', 'Permission berhasil dihapus');
    }
}
