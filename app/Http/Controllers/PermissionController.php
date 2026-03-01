<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
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
        $permissions = Permission::withCount('roles')
            ->paginate(15);
        return view('dashboard.pages.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('dashboard.pages.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:100|regex:/^[a-z0-9\.\-\_]+$/',
        ]);

        Permission::create(['name' => $validated['name']]);

        Alert::success('Permission Berhasil Dibuat', 'Permission ' . $validated['name'] . ' berhasil dibuat.');
        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dibuat.');
    }

    public function edit(Permission $permission)
    {
        return view('dashboard.pages.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id . '|max:100|regex:/^[a-z0-9\.\-\_]+$/',
        ]);

        $permission->update(['name' => $validated['name']]);

        Alert::success('Permission Berhasil Diupdate', 'Permission ' . $validated['name'] . ' berhasil diupdate.');

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil diupdate.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        Alert::success('Permission Berhasil Dihapus', 'Permission ' . $permission->name . ' berhasil dihapus.');
        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dihapus.');
    }
}