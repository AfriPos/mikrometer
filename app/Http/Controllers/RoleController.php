<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        try {
            // Create the role
            $role = Role::create([
                'name' => $request->name,
            ]);

            // Log the creation of the role
            // activity()
            //     ->causedBy(auth()->user())
            //     ->performedOn($role)
            //     ->withProperties(['action' => 'created'])
            //     ->log('Role "' . $role->name . '" created');

            // Redirect back with a success message
            return redirect()->route('admin.roles.index');
        } catch (\Exception $e) {
            // If an exception occurs during update, show error message and repopulate old input
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create role']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            // Other fields to validate
        ]);

        try {
            // Update the role
            $role->update([
                'name' => $request->name,
                // Other fields to update
            ]);

            // Log the creation of the role
            // activity()
            //     ->causedBy(auth()->user())
            //     ->performedOn($role)
            //     ->withProperties(['action' => 'updated'])
            //     ->log('Role "' . $role->name . '" updated');

            // Redirect back with a success message
            return redirect()->route('admin.roles.index');
        } catch (\Exception $e) {
            // If an exception occurs during update, show error message and repopulate old input
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update role']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
    }

    public function editpermissions($role)
    {
        $permissions = Permission::get();
        $role = Role::findorFail($role);
        return view('admin.roles.editpermissions', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function givePermissionsToRole(Request $request, $role)
    {
        // Validate the form submission
        $validatedData = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        // Find the role
        $role = Role::findOrFail($role);

        // Retrieve the old permissions
        $oldPermissions = $role->permissions->pluck('name')->toArray();

        // Retrieve permission objects for the given permission names
        $permissions = Permission::whereIn('name', $validatedData['permissions'])->get();

        // Update the role's permissions
        $role->syncPermissions($permissions);

        // Retrieve the new permissions
        $newPermissions = $role->permissions->pluck('name')->toArray();

        // Find only the newly granted permissions
        $grantedPermissions = array_diff($newPermissions, $oldPermissions);

        // Find the old permissions excluding the newly granted ones
        $remainingOldPermissions = array_diff($oldPermissions, $newPermissions);

        // Find the revoked permissions
        $revokedPermissions = array_diff($oldPermissions, $newPermissions);

        // Log the changes if there are new permissions granted or revoked
        if (!empty($grantedPermissions) || !empty($revokedPermissions)) {
            $changes = [
                'role' => $role->name,
                'old_permissions' => $remainingOldPermissions,
                'granted_permissions' => $grantedPermissions,
                'revoked_permissions' => $revokedPermissions,
            ];

            // activity()
            //     ->causedBy(auth()->user())
            //     ->performedOn($role)
            //     ->withProperties(['action' => 'updated permissions', 'changes' => $changes])
            //     ->log('Permissions updated for role: ' . $role->name);
        }

        return redirect()->back()->with('success', 'Permissions updated successfully');
    }
}
