<?php

namespace App\Http\Controllers\admin\Accounts\AccountStatus;

use App\Http\Controllers\Controller;
use App\Models\admin\Accounts\AccountStatus\AccountStatusesModel;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AccountStatusesController extends Controller implements HasMiddleware
{

     public static function middleware(): array
    {
        return [
            new Middleware('permission:View Status', only: ['index']),
            new Middleware('permission:Edit Status', only: ['edit']),
            new Middleware('permission:Create Status', only: ['create']),
            new Middleware('permission:Delete Status', only: ['destroy']),
        ];
    }
    // this function will show index page of account statuses
    public function index()
    {
        $accountStatuses = AccountStatusesModel::paginate(10);

        return view('admin.dashboard.accounts.account-status.list', compact('accountStatuses'));
    }

    // this function will show create page of account statuses
    public function create()
    {
        return view('admin.dashboard.accounts.account-status.create');
    }

    // this function will store account status in database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:status,name',
            'slug' => 'required|string|max:255|unique:status,slug',
            'description' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $accountStatus = new AccountStatusesModel;
        $accountStatus->name = $request->name;
        $accountStatus->slug = $request->slug;
        $accountStatus->description = $request->description;
        $accountStatus->save();
        if ($accountStatus) {
            session()->flash('success', 'Account status created successfully.');

            return response()->json(['success' => 'Account status created successfully.'], 200);
        } else {
            session()->flash('error', 'Failed to create account status.');

            return response()->json(['error' => 'Failed to create account status.'], 500);
        }
    }

    // this function will show edit page of account statuses
    public function edit($id)
    {
        $accountStatus = AccountStatusesModel::find($id);
        if (! $accountStatus) {
            session()->flash('error', 'Account status not found.');

            return redirect()->route('admin.status.index');
        }

        return view('admin.dashboard.accounts.account-status.edit', compact('accountStatus'));
    }

    // this function will update account status in database
    public function update(Request $request, $id)
    {
        $accountStatus = AccountStatusesModel::find($id);
        if (! $accountStatus) {
            session()->flash('error', 'Account status not found.');

            return response()->json(['error' => 'Account status not found.'], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:status,name,'.$accountStatus->id,
            'slug' => 'required|string|max:255|unique:status,slug,'.$accountStatus->id,
            'description' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $accountStatus->name = $request->name;
        $accountStatus->slug = $request->slug;
        $accountStatus->description = $request->description;
        $accountStatus->save();
        if ($accountStatus) {
            session()->flash('success', 'Account status updated successfully.');

            return response()->json(['success' => 'Account status updated successfully.'], 200);
        } else {
            session()->flash('error', 'Failed to update account status.');

            return response()->json(['error' => 'Failed to update account status.'], 500);
        }

    }

    // this function will delete account status from database
    public function destroy($id)
    {
        $accountStatus = AccountStatusesModel::find($id);
        if (! $accountStatus) {
            session()->flash('error', 'Account status not found.');

            return response()->json(['error' => 'Account status not found.'], 404);
        }
        $accountStatus->delete();
        if ($accountStatus) {
            session()->flash('success', 'Account status deleted successfully.');
            return response()->json(['success' => 'Account status deleted successfully.'], 200);
        } else {
            session()->flash('error', 'Failed to delete account status.');
            return response()->json(['error' => 'Failed to delete account status.'], 500);
        }
    }
}
