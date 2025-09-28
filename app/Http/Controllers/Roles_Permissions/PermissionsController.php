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
    $permissions = Permission::select('id','name','guard_name','created_at')
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
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:permissions|min:3',
            ]);
        if ($validator->passes()) {
            $permission = Permission::create(
                [
                    'name' => $request->name,
                ]);
            if ($permission) {
                return redirect()->route('admin.permissions.index')->with('success', 'Data Saved Successfully');
            } else {
                return redirect()->route('admin.permissions.create')->withInput()->withErrors($validator)->with('error', 'Data not saved due to error');
            }
        } else {
            return redirect()->route('admin.permissions.create')->withInput(request()->all())->withErrors($validator);
        }
    }

    // this method shows permissions edit page
    public function edit() {}

    // this method shows update permissions
    public function update() {}

    // this method delete permission from db
    public function destroy() {}
}
