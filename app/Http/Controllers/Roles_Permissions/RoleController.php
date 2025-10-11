<?php

namespace App\Http\Controllers\Roles_Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // this method will show role page
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->paginate(10);

        return view('admin.dashboard.auth.roles.list', compact('roles'));
    }

    // this method will show create role page
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.dashboard.auth.roles.create', compact('permissions'));
    }

    // this method will store role in db
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|unique:roles,name',
            'slug' => 'required|string|min:3|unique:roles,slug',
            'is_active' => 'nullable|in:0,1',
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Failed to save role');

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'is_active' => $request->is_active ?? 1,
        ]);

        if (! empty($request->permissions)) {
            // foreach($request->permissions as $permission){
            //     $role->givePermissionTo($permission);
            // }
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }
        session()->flash('success', 'Role created successfully');

        return response()->json([
            'status' => true,
            'success' => 'Role created successfully',
        ]);
    }


    //this method will delete role from db
    public function destroy(Request $request,$id) {
        $role = Role::find($id);
        if(!$role)
        {
            session()->flash('error', 'Role Not Found');
             return response()->json(['error' , 'Role Not Found']);
        }
        else
        {
            $delete = $role->delete();
            if($delete)
            {
            session()->flash('success', 'Role deleted successfully');
             return response()->json(['success' , 'Role Deleted Successfully']);

            }
        }
    }   


    //this method will show edit role page
    public function edit($id)
    {
        $role = Role::find($id);
        if (! $role) {
            session()->flash('error', 'Role Not Found');
            return redirect()->route('admin.roles.index');
        }
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.dashboard.auth.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }


      public function update(Request $request, $id)
{
    $role = Role::find($id);

    if (!$role) {
        session()->flash('error', 'Role Not Found');

        return response()->json([
            'status' => false,
            'errors' => 'Role Not Found',
        ], 422);
    }

    $validator = Validator::make($request->all(), [
         'name' => 'required|string|min:3|unique:roles,name,' .$role->id,
            'slug' => 'required|string|min:3|unique:roles,slug,'.$role->id,
            'is_active' => 'nullable|in:0,1',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

    if ($validator->fails()) {
        session()->flash('error', 'Failed to save role');

        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    $role->update([
        'name' => $request->name,
        'slug' => $request->slug,
        'is_active' => $request->input('is_active', 1),
    ]);

    if (!empty($request->permissions)) {
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);
    } else {
        // If you want to clear permissions when none sent, uncomment:
        // $role->syncPermissions([]);
    }

    session()->flash('success', 'Role updated successfully');

    return response()->json([
        'status' => true,
        'success' => 'Role updated successfully',
    ]);
}

    
}

