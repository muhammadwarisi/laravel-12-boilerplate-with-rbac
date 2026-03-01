<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:user.view', only: ['index', 'show']),
            new Middleware('permission:user.create', only: ['create', 'store']),
            new Middleware('permission:user.edit', only: ['edit', 'update']),
            new Middleware('permission:user.delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $roles = Role::withCount('users', 'permissions')->paginate(10);
        return view('dashboard.pages.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(fn($p) => explode('.', $p->name)[0]);
        return view('dashboard.pages.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|unique:roles,name|max:100',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        Alert::success('Role Berhasil Dibuat', 'Role ' . $validated['name'] . ' berhasil dibuat.');

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(fn($p) => explode('.', $p->name)[0]);
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('dashboard.pages.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name'        => 'required|string|unique:roles,name,' . $role->id . '|max:100',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);
        Alert::success('Role Berhasil Diupdate', 'Role ' . $validated['name'] . ' berhasil diupdate.');
        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diupdate.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super-admin'])) {
            return back()->with('error', 'Role super-admin tidak dapat dihapus.');
        }

        $role->delete();
        Alert::success('Role Berhasil Dihapus', 'Role ' . $role->name . ' berhasil dihapus.');
        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}