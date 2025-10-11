<?php

namespace App\Http\Controllers\admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //this function will return the list of users
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.dashboard.User.list', compact('users'));
    }
    //this function will return the edit user form
    public function edit($id)
    {
        $roles= Role::orderBy('name')->get();
        $users = User::find($id);
        if (!$users) {
            session()->flash('error', 'User not found.');
            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        }
        return view('admin.dashboard.User.edit', compact('users','roles'));
    }

}
