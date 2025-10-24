<?php

namespace App\Http\Controllers\Roles_Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class PermissionsController extends Controller implements HasMiddleware
{

    //this function only apply middlewares
    public static function middleware(): array
{
    return [
        new Middleware('permission:View Permissions', only: ['index']),
        new Middleware('permission:Edit Permissions', only: ['edit']),
        new Middleware('permission:Create Permissions', only: ['create']),
        new Middleware('permission:Delete Permissions', only: ['destroy']),
    ];
}

    // this method shows permissions page
    public function index()
    {
        $permissions = Permission::select('id', 'name', 'slug', 'is_active', 'guard_name', 'created_at')
            ->orderByDesc('id')->paginate(10);

        return view('admin.dashboard.Auth.permissions.list', compact('permissions'));
    }

    // this method shows create permissions page
    public function create()
    {
        return view('admin.dashboard.Auth.permissions.create');
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
    // public function edit($id) {
    //     $permission = Permission::findById($id);
    //     if(!$permission)
    //     {
    //         session()->flash('error', 'Permissions Not Found');
    //         return redirect()->route('admin.permissions.index');
    //     }
    //     return view('admin.dashboard.auth.permissions.edit', compact('permission'));
    // }

    public function edit($id)
{
    try {
        $permission = Permission::findById($id, 'web');
    } catch (PermissionDoesNotExist $e) {
        session()->flash('error', 'Permission not found.');
        return redirect()->route('admin.permissions.index');
    }

    return view('admin.dashboard.Auth.permissions.edit', compact('permission'));
}


    // this method shows update permissions
    public function update(Request $request , $id ) {
        $permission = Permission::findById($id);
        if(!$permission)
        {
            session()->flash('error', 'Permissions Not Found');
            return response()->json(['error' , 'Permissions Not Found']);
        }

        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|min:3|unique:permissions,name,' .$permission->id,
            'slug' => 'required|string|min:3|unique:permissions,slug,'.$permission->id,
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
        $permission = $permission->update($data);
        session()->flash('success', 'Permission Updated successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Permission Updated successfully.',
        ], 201);


    }

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
