<?php

namespace App\Http\Controllers\Roles_Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //this method will show role page
    public function index()
    {
        // return view('admin.dashboard.auth.roles.list');
    }

    // this method will show create role page
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.dashboard.auth.roles.create', compact('permissions'));
    }

    //this method will store role in db
    public function store(Request $request){}

}
