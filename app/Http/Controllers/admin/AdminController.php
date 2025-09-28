<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function Dashboard()
    {
        return view("admin.dashboard.dashboard");
    }


    public function profile()
    {
        $user = auth()->user();
        return view("admin.dashboard.Auth.profile",["user"=> $user]);
    }
}
