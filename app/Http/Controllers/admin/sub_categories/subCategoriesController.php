<?php

namespace App\Http\Controllers\admin\sub_categories;

use App\Http\Controllers\Controller;
use App\Models\admin\categories\CategoriesModel;
use App\Models\admin\sub_categories\subCategoriesModel;
use App\Models\admin\tempImageModel;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Validator;
class subCategoriesController extends Controller implements HasMiddleware
{
    
     public static function middleware(): array
{
    return [
        new Middleware('permission:View SubCategories', only: ['index']),
        new Middleware('permission:Edit SubCategories', only: ['edit']),
        new Middleware('permission:Create SubCategories', only: ['create']),
        new Middleware('permission:Delete SubCategories', only: ['destroy']),
    ];
}

    // this function will return the list of categories
    public function index()
    {
        $sub_categories = subCategoriesModel::orderBy('created_at', 'desc')->with('category')->paginate(10);

        return view('admin.dashboard.sub_categories.list', compact('sub_categories'));
    }

    // this function will return the create category view
    public function create()
    {
        $categories = CategoriesModel::orderBy('name', 'asc')->get();
        return view('admin.dashboard.sub_categories.create', compact('categories'));
    }

    // this function will store the category in the database
    public function store(Request $request)
    {
       $validator =  Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sub_categories,name',
            'description' => 'nullable|string|max:1000',
            'slug' => 'required|string|max:255|unique:sub_categories,slug',
            'is_active' => 'required|boolean',
            'category_id' => 'required|exists:category,id',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $category = subCategoriesModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug,
            'is_active' => $request->is_active,
            'category_id' => $request->category_id,
        ]);


         if (!empty($request->image_id)) {
            $tempimage = tempImageModel::find($request->image_id);
            if ($tempimage) {
                $extArray = explode('.', $tempimage->name);
                $ext = last($extArray);

                $newImageName = 'sub_category_' . time() . '.' . $ext;
                $newImagePath = 'uploads/sub_categories/' . $newImageName;
                File::copy(public_path('uploads/temp/' . $tempimage->name), public_path($newImagePath));
                // Resize image
                $img = Image::read(public_path('uploads/temp/' . $tempimage->name));
                $img->resize(800, 800);
                $img->save(public_path('uploads/sub_categories/thumb/' . $newImageName));

                $category->image = $newImageName;
                $category->save();


            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image not found.',
                ], 404);
            }

        }

        if ($category) {
            session()->flash('success', 'Sub Category created successfully.');

            return response()->json([
                'status' => 'success',
                'message' => 'Sub Category created successfully.',
                'data' => $category,
            ], 201);
        }
        else{
            session()->flash('error', 'Failed to create sub category.');
            return redirect()->back()->withInput()->withErrors('Failed to create sub category.');
        }

    }


    public function destroy($id)
    {
        $sub_category = subCategoriesModel::find($id);
        if (!$sub_category) {
            session()->flash('error', 'Sub Category Not Found.');

            return response()->json([
                'status' => 'error',
                'message' => 'Sub Category not found.',
            ], 404);
        }

        if ($sub_category->delete()) {
            session()->flash('success', 'Sub Category deleted successfully.');

            return response()->json([
                'status' => 'success',
                'message' => 'Sub Category deleted successfully.',
            ], 200);
        } else {
            session()->flash('error', 'Failed to delete sub category.');

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete sub category.',
            ], 500);
        }
    }

    //this function will return the edit category view
    public function edit($id)
    {
        $sub_category = subCategoriesModel::find($id);
        $categories = CategoriesModel::orderBy('name', 'asc')->get();
        if (!$sub_category) {
            session()->flash('error', 'Sub Category Not Found.');
            return redirect()->back()->withErrors('Sub Category not found.');
        }
        return view('admin.dashboard.sub_categories.edit', compact('sub_category', 'categories'));
    }
    // this function will update the category in the database
    public function update(Request $request, $id)
    {
        $category = subCategoriesModel::find($id);

        if (!$category) {
            session()->flash('error', 'Sub Category Not Found.');
            return redirect()->back()->withErrors('Sub Category not found.');
        }
        $validator =  Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sub_categories,name,'.$id,
            'description' => 'nullable|string|max:1000',
            'slug' => 'required|string|max:255|unique:sub_categories,slug,'.$id,
            'category_id' => 'required|exists:category,id',
            'is_active' => 'required|boolean',
        ]);
        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }




         if (!empty($request->image_id)) {
            $tempimage = tempImageModel::find($request->image_id);
            if ($tempimage) {
                $extArray = explode('.', $tempimage->name);
                $ext = last($extArray);

                $newImageName = 'sub_category_' . time() . '.' . $ext;
                $newImagePath = 'uploads/sub_categories/' . $newImageName;
                File::delete(public_path('uploads/sub_categories/' . $category->image));
                File::delete(public_path('uploads/sub_categories/thumb/' . $category->image));
                File::copy(public_path('uploads/temp/' . $tempimage->name), public_path($newImagePath));
                // Resize image
                $img = Image::read(public_path('uploads/temp/' . $tempimage->name));
                $img->resize(800, 800);
                $img->save(public_path('uploads/sub_categories/thumb/' . $newImageName));

                $category->image = $newImageName;
                $category->save();


            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image not found.',
                ], 404);
            }
        }


        
        $category->name = $request->name;
        $category->description = $request->description;
        $category->slug = $request->slug;
        $category->is_active = $request->is_active;
        $category->category_id = $request->category_id;


        if ($category->save()) {
            session()->flash('success', 'Sub Category updated successfully.');
            return response()->json([
                'status' => 'success',
                'message' => 'Sub Category updated successfully.',
                'data' => $category,
            ], 200);
        } else {
            session()->flash('error', 'Failed to update sub category.');
            return redirect()->back()->withInput()->withErrors('Failed to update sub category.');
        }
    }
}
