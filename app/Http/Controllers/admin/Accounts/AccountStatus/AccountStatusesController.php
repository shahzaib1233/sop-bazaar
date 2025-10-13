<?php

namespace App\Http\Controllers\admin\Accounts\AccountStatus;

use App\Http\Controllers\Controller;
use App\Models\admin\Accounts\AccountStatus\AccountStatusesModel;
use Illuminate\Http\Request;
use Validator;

class AccountStatusesController extends Controller
{
    //this function will show index page of account statuses
    public function index()
    {
        $accountStatuses = AccountStatusesModel::paginate(10);
        return view('admin.dashboard.accounts.account-status.list', compact('accountStatuses'));
    }

    //this function will show create page of account statuses
    public function create()
    {
        return view('admin.dashboard.accounts.account-status.create');
    }

    //this function will store account status in database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:status,name',
            'slug' => 'required|string|max:255|unique:status,slug',
            'description' => 'nullable|string|max:1000',
        ]);
        if($validator->fails()){
            session()->flash('error', 'There were some problems with your input.');
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $accountStatus = new AccountStatusesModel();
        $accountStatus->name = $request->name;
        $accountStatus->slug = $request->slug;
        $accountStatus->description = $request->description;
        $accountStatus->save();
        if($accountStatus){
            session()->flash('success', 'Account status created successfully.');
            return response()->json(['success' => 'Account status created successfully.'], 200);
        }else{
            session()->flash('error', 'Failed to create account status.');
            return response()->json(['error' => 'Failed to create account status.'], 500);
        }
    }
    
}


