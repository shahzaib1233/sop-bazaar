<?php

namespace App\Http\Controllers\Roles_Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    // this method shows permissions page
    // app/Http/Controllers/Admin/PermissionController.php
    public function index()
    {
        $permissions = Permission::select('id', 'name', 'slug', 'is_active', 'guard_name', 'created_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.dashboard.auth.permissions.list', compact('permissions'));
    }

    // this method shows create permissions page
    public function create()
    {
        return view('admin.dashboard.auth.permissions.create');
    }

    // this method store permissions data
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|unique:permissions,name',
            'slug' => 'required|string|min:3|unique:permissions,slug',
            'is_active' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['is_active'] = isset($data['is_active']) ? (int) $data['is_active'] : 1;

        $permission = Permission::create($data);
        session()->flash('success', 'Permission saved successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Permission saved successfully.',
        ], 201);
    }

    // this method shows permissions edit page
    public function edit() {}

    // this method shows update permissions
    public function update() {}

    // this method delete permission from db
    public function destroy(Request $request,$id) {
        $permission = Permission::findOrFail($id);
        if(!$permission)
        {
            session()->flash('error', 'Permissions Not Found');
            return response()->json(['error' , 'Permissions Not Found']);
        }
        else
        {
            $delete = $permission->delete();
            if($delete)
            {
            session()->flash('success', 'Permission saved successfully');
             return response()->json(['success' , 'Permissions Deleted Successfully']);

            }
        }
    }   
}
