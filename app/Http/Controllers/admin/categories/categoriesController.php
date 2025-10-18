<?php

namespace App\Http\Controllers\admin\categories;

use App\Http\Controllers\Controller;
use App\Models\admin\categories\CategoriesModel;
use App\Models\admin\tempImageModel;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Validator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class categoriesController extends Controller implements HasMiddleware
{

     public static function middleware(): array
    {
        return [
            new Middleware('permission:View Categories', only: ['index']),
            new Middleware('permission:Edit Categories', only: ['edit']),
            new Middleware('permission:Create Categories', only: ['create']),
            new Middleware('permission:Delete Categories', only: ['destroy']),
        ];
    }

    // this function will return the list of categories
    public function index()
    {
        $categories = CategoriesModel::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.dashboard.categories.list', compact('categories'));
    }

    // this function will return the create category view
    public function create()
    {
        return view('admin.dashboard.categories.create');
    }

    // this function will store the category in the database
    public function store(Request $request)
    {
       $validator =  Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:category,name',
            'description' => 'nullable|string|max:1000',
            'slug' => 'required|string|max:255|unique:category,slug',
            'is_active' => 'required|boolean',
            'image_id' => 'nullable|exists:temp_images,id',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = CategoriesModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug,
            'is_active' => $request->is_active,
        ]);
        if (!empty($request->image_id)) {
            $tempimage = tempImageModel::find($request->image_id);
            if ($tempimage) {
                $extArray = explode('.', $tempimage->name);
                $ext = last($extArray);

                $newImageName = 'category_' . time() . '.' . $ext;
                $newImagePath = 'uploads/categories/' . $newImageName;
                File::copy(public_path('uploads/temp/' . $tempimage->name), public_path($newImagePath));
                // Resize image
                $img = Image::read(public_path('uploads/temp/' . $tempimage->name));
                $img->resize(800, 800);
                $img->save(public_path('uploads/categories/thumb/' . $newImageName));

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
            session()->flash('success', 'Category created successfully.');

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully.',
                'data' => $category,
            ], 201);
        }
        else{
            session()->flash('error', 'Failed to create category.');
            return redirect()->back()->withInput()->withErrors('Failed to create category.');
        }

    }


    public function destroy($id)
    {
        $category = CategoriesModel::find($id);
        if (!$category) {
            session()->flash('error', 'Category Not Found.');

            return response()->json([
                'status' => 'error',
                'message' => 'Category not found.',
            ], 404);
        }

        if ($category->delete()) {
            session()->flash('success', 'Category deleted successfully.');

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully.',
            ], 200);
        } else {
            session()->flash('error', 'Failed to delete category.');

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete category.',
            ], 500);
        }
    }

    //this function will return the edit category view
    public function edit($id)
    {
        $category = CategoriesModel::find($id);
        if (!$category) {
            session()->flash('error', 'Category Not Found.');
            return redirect()->back()->withErrors('Category not found.');
        }
        return view('admin.dashboard.categories.edit', compact('category'));
    }
    // this function will update the category in the database
    public function update(Request $request, $id)
    {
        $category = CategoriesModel::find($id);
        
        if (!$category) {
            session()->flash('error', 'Category Not Found.');
            return redirect()->back()->withErrors('Category not found.');
        }
        $validator =  Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:category,name,'.$id,
            'description' => 'nullable|string|max:1000',
            'slug' => 'required|string|max:255|unique:category,slug,'.$id,
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

                $newImageName = 'category_' . time() . '.' . $ext;
                $newImagePath = 'uploads/categories/' . $newImageName;
                File::delete(public_path('uploads/categories/' . $category->image));
                File::delete(public_path('uploads/categories/thumb/' . $category->image));
                File::copy(public_path('uploads/temp/' . $tempimage->name), public_path($newImagePath));
                // Resize image
                $img = Image::read(public_path('uploads/temp/' . $tempimage->name));
                $img->resize(800, 800);
                $img->save(public_path('uploads/categories/thumb/' . $newImageName));

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
        if ($category->save()) {
            session()->flash('success', 'Category updated successfully.');
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully.',
                'data' => $category,
            ], 200);
        } else {
            session()->flash('error', 'Failed to update category.');
            return redirect()->back()->withInput()->withErrors('Failed to update category.');
        }
    }
}
