<?php

namespace App\Http\Controllers\admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class UserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
{
    return [
        new Middleware('permission:View Users', only: ['index']),
        new Middleware('permission:Edit Users', only: ['edit']),
        new Middleware('permission:Create Users', only: ['create']),
        new Middleware('permission:Delete Users', only: ['destroy']),
    ];
}


    // this function will return the list of users
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.dashboard.User.list', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.dashboard.User.create', compact('roles'));
    }
    // this function will return the edit user form
    public function edit($id)
    {
        $roles = Role::orderBy('name')->get();
        $users = User::find($id);
        if (! $users) {
            session()->flash('error', 'User not found.');

            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        }

        return view('admin.dashboard.User.edit', compact('users', 'roles'));
    }

  

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status' => false,
                'errors' => 'User not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|min:3|max:255|unique:users,email,'.$user->id,
            'role' => 'required|array',
            'role.*' => 'exists:roles,id',
            'is_active' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = Role::find($request->role);
        if (! $role) {
            return response()->json([
                'status' => false,
                'errors' => ['role' => ['The selected role does not exist.']],
            ], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->is_active,
        ]);

        $user->syncRoles([$role]);

        session()->flash('success', 'User updated successfully.');
        return response()->json([
            'status' => true,
            'message' => 'User updated successfully.',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'password' => 'required|min:6|max:255|confirmed',
            'role' => 'required|array',
            'role.*' => 'exists:roles,id',
            'is_active' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = Role::find($request->role);
        if (! $role) {
            return response()->json([
                'status' => false,
                'errors' => ['role' => ['The selected role does not exist.']],
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active,
        ]);

        $user->assignRole($role);

        session()->flash('success', 'User created successfully.');
        return response()->json([
            'status' => true,
            'message' => 'User created successfully.',
        ]);
    }   
}
