<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ACLController extends Controller
{
    public function index()
    {
        // https://spatie.be/docs/laravel-permission/v6/basic-usage/basic-usage
        $permissions = Permission::all();
        $roles = Role::all();

        return view('acl.index', compact('roles', 'permissions'));
    }

    public function updatePermissions(Permission $permission, Request $request)
    {
        $roles = Role::all();
        $data = array_keys($request->except(['_token', '_method']));

        foreach($roles as $role) {
            if (in_array($role->name, $data)) {
                $permission->assignRole($role);
                continue;
            }

            if ($role->hasPermissionTo($permission->name)) {
                $permission->removeRole($role);
            }
        }

        return redirect()->route('acl.index')->with('message', 'success|ACL actualizado');
    }

    public function createRole()
    {
        return view('acl.role.create');
    }

    public function storeRole(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        Role::create($request->all());

        return redirect()->back();
    }

    public function deleteRole(Request $request)
    {
        // only super-admin can do this
    }

    public function createPermission()
    {
        return view('acl.permission.create');
    }

    public function storePermission(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        // format: verb(space)resource

        Permission::create($request->toArray());

        return redirect()->back()->with('message', 'success|Permiso creado');
    }

    public function assignPermissionToRole(Role $role, string $permissionName)
    {
        $role->givePermissionTo($permissionName);

        return redirect()->back();
    }

    public function removePermissionFromRole(Role $role, string $permissionName)
    {
        $role->revokePermissionTo($permissionName);

        return redirect()->back();
    }
}
