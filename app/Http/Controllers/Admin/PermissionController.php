<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'permissions')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Show permissions for a specific user
    public function edit(User $user)
    {
        $permissions = Permission::all();
        return view('admin.users.permissions', compact('user', 'permissions'));
    }

    // Update permissions for a user
    public function update(Request $request, User $user)
    {
        $user->syncPermissions($request->permissions ?? []);
        return redirect()->route('admin.users.index')->with('success', 'Permissions updated!');
    }
}
