<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name'=> 'required','min:5']);
        
        Permission::create($validated);

        return to_route('admin.permissions.index')->with('message','Permission Created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $roles = Role::whereNotIn('name',['admin'])->get();
        return view('admin.permissions.edit',compact('permission','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate(['name'=> 'required','min:5']);

        $permission->update($validated);

        return to_route('admin.permissions.index')->with('message','Permission Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return to_route('admin.permissions.index')->with('message','Permission Deleted successfully.');
    }

    public function assignRole(Request $request, Permission $permission)
    {
        if($permission->hasRole($request->role)){
            return back()->with('message','Role exists.');
        }

        $permission->assignRole($request->role);
        return back()->with('message','Role Assigned.');
    }

    public function removeRole(Permission $permission,Role $role)
    {
        if($permission->hasRole($role)){
            $permission->removeRole($role);
            return back()->with('message','Role removed.');
        }

        return back()->with('message','Role not exists.');
    }
}
