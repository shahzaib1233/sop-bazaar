<?php

namespace App\Http\Controllers\admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class UserController extends Controller 
{

    public static function middleware(): array
{
    return [
        new Middleware('permission:view users', only: ['index']),
        new Middleware('permission:edit users', only: ['edit']),
        new Middleware('permission:create users', only: ['create']),
        new Middleware('permission:delete users', only: ['destroy']),
    ];
}


    // this function will return the list of users
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.dashboard.User.list', compact('users'));
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

    // this function will update the user
    // public function update(Request $request, $id)
    // {
    //     $users = User::find($id);
    //     if (!$users) {
    //         session()->flash('error', 'User not found.');
    //         return redirect()->route('admin.users.index')->with('error', 'User not found.');
    //     }
    //        $validator = Validator::make($request->all(), [
    //     'name'  => 'required|min:3|max:255',
    //     'email' => 'required|email|min:3|max:255|unique:users,email,' . $users->id,
    //     'role'  => 'required|exists:roles,id',
    //     'status'=> 'required|in:0,1',
    // ]);
    //     if($validator->fails()){
    //         session()->flash('error', 'User Not Updated Please fix the errors first.');
    //         return response()->json(['error' => 'User Not Updated Please fix the errors first.']);
    //     }

    //     $users->name = $request->name;
    //     $users->email = $request->email;
    //     $users->status = $request->status;
    //     $users->save();
    //     $users->syncRoles($request->role);
    //     return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');

    // }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status' => false,
                'errors' => 'User not found.',
            ], 404);
        }

        // $validator = Validator::make($request->all(), [
        //     'name'      => 'required|min:3|max:255',
        //     'email'     => 'required|email|min:3|max:255|unique:users,email,' . $user->id,
        //     'role'      => 'required|exists:roles,id',
        //     'is_active' => 'required|in:0,1',
        // ]);

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
}
