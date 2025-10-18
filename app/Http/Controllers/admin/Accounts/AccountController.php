<?php

namespace App\Http\Controllers\admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\admin\Accounts\AccountModel;
use App\Models\admin\Accounts\AccountsImageModel;
use App\Models\admin\accounts\AccountsTagsModel;
use App\Models\admin\Accounts\AccountStatus\AccountStatusesModel;
use App\Models\admin\categories\CategoriesModel;
use App\Models\admin\sub_categories\subCategoriesModel;
use App\Models\admin\tempImageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class AccountController extends Controller implements HasMiddleware
{

public static function middleware(): array
    {
        return [
            new Middleware('permission:View Accounts', only: ['index']),
            new Middleware('permission:Edit Accounts', only: ['edit']),
            new Middleware('permission:Create Accounts', only: ['create']),
            new Middleware('permission:Delete Accounts', only: ['destroy']),
        ];
    }
        public function index()
    {
        $accounts = AccountModel::with(['category', 'subcategory', 'status', 'creator', 'images'])->latest()->paginate(10);

        return view('admin.dashboard.accounts.accounts.list', compact('accounts'));
    }

    public function create()
    {
        $categories = CategoriesModel::all();
        $subcategories = subCategoriesModel::all();
        $statuses = AccountStatusesModel::all();

        return view('admin.dashboard.accounts.accounts.create', compact('categories', 'subcategories', 'statuses'));
    }

    public function store(Request $request)
    {
        $account = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:accounts,slug',
            'category_id' => 'required|exists:category,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|integer|min:1',
            'status_id' => 'required|exists:status,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // $ip = $request->ip();
        // $geo = null;
        // try {
        //     $geo = \GeoIp::getLocation($ip);
        // } catch (\Exception $e) {
        // }

        $account = AccountModel::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'price' => $request->price,
            'status_id' => $request->status_id,
            'description' => $request->description,
            'is_active' => true,
            'tags' => $request->tags ? json_encode($request->tags) : null,
            'created_by' => auth()->id(),
            // 'ip_address' => $ip,
            // 'geoip_data' => $geo ? json_encode($geo) : null,
        ]);
            if (json_encode($request->tags) ) {
                foreach ($request->tags as $tag) {
                    AccountsTagsModel::create([
                        'account_id' => $account->id,
                        'tag' => $tag,
                    ]);
                }
            }

        
        if (! empty($request->image_id)) {
            $imageIds = explode(',', $request->image_id);
            foreach ($imageIds as $tempId) {
                $tempimage = tempImageModel::find($tempId);
                if ($tempimage) {
                    $extArray = explode('.', $tempimage->name);
                    $ext = last($extArray);

                    $newImageName = 'account_'.time().'_'.uniqid().'.'.$ext;
                    $newImagePath = 'uploads/accounts/'.$newImageName;

                    File::copy(public_path('uploads/temp/'.$tempimage->name), public_path($newImagePath));

                    $img = Image::read(public_path('uploads/temp/'.$tempimage->name));
                    $img->resize(800, 800);
                    $img->save(public_path('uploads/accounts/thumb/'.$newImageName));

                    $accounts_image = AccountsImageModel::create([
                        'account_id' => $account->id,
                        'image' => $newImageName,
                        'is_primary' => false,
                    ]);
                    if (! $accounts_image) {
                        session()->flash('error', 'Failed to save account image.');

                        return response()->json(['success' => false], 500);
                    }

                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Image not found.',
                    ], 404);
                }
            }
        }

        if (! $account) {
            session()->flash('error', 'Failed to create account.');

            return response()->json(['success' => false], 500);
        }
        session()->flash('success', 'Account created successfully.');

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $account = AccountModel::find($id);
        if (! $account) {
            session()->flash('error', 'Account not found.');

            return redirect()->back();
        }

        // Delete associated images
        foreach ($account->images as $image) {
            $imagePath = public_path('uploads/accounts/'.$image->image);
            $thumbPath = public_path('uploads/accounts/thumb/'.$image->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }

            $image->delete();
        }

        if ($account->delete()) {
            session()->flash('success', 'Account deleted successfully.');

            return response()->json(['success' => true]);
        } else {
            session()->flash('error', 'Failed to delete account.');

            return response()->json(['success' => false]);
        }

    }

    public function edit($id)
    {
        $account = AccountModel::with('images')->find($id);
        if (! $account) {
            session()->flash('error', 'Account not found.');

            return redirect()->back();
        }
        $categories = CategoriesModel::all();
        $subcategories = subCategoriesModel::all();
        $statuses = AccountStatusesModel::all();

        return view('admin.dashboard.accounts.accounts.edit', compact('account', 'categories', 'subcategories', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $account = AccountModel::find($id);
        if (! $account) {
            session()->flash('error', 'Account not found.');

            return response()->json(['success' => false], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:accounts,slug,'.$account->id.',id',
            'category_id' => 'required|exists:category,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|integer|min:1',
            'status_id' => 'required|exists:status,id',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $account->name = $request->name;
        $account->slug = $request->slug;
        $account->category_id = $request->category_id;
        $account->subcategory_id = $request->subcategory_id;
        $account->price = $request->price;
        $account->status_id = $request->status_id;
        $account->description = $request->description;
        $account->is_active = $request->has('is_active') ? $request->is_active : false;
        $account->tags = $request->tags ? json_encode($request->tags) : null;
        if (! $account->save()) {
            session()->flash('error', 'Failed to update account.');

            return response()->json(['success' => false], 500);
        }
        if (! empty($request->image_id)) {
            $imageIds = explode(',', $request->image_id);
            foreach ($imageIds as $tempId) {
                $tempimage = tempImageModel::find($tempId);
                if ($tempimage) {
                    $extArray = explode('.', $tempimage->name);
                    $ext = last($extArray);

                    $newImageName = 'account_'.time().'_'.uniqid().'.'.$ext;
                    $newImagePath = 'uploads/accounts/'.$newImageName;

                    File::copy(public_path('uploads/temp/'.$tempimage->name), public_path($newImagePath));

                    $img = Image::read(public_path('uploads/temp/'.$tempimage->name));
                    $img->resize(800, 800);
                    $img->save(public_path('uploads/accounts/thumb/'.$newImageName));

                    $accounts_image = AccountsImageModel::create([
                        'account_id' => $account->id,
                        'image' => $newImageName,
                        'is_primary' => false,
                    ]);
                    if (! $accounts_image) {
                        session()->flash('error', 'Failed to save account image.');

                        return response()->json(['success' => false], 500);
                    }

                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Image not found.',
                    ], 404);
                }
            }
        }
        session()->flash('success', 'Account updated successfully.');

        return response()->json(['success' => true]);
    }
}
