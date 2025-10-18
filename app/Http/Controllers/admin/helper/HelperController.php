<?php

namespace App\Http\Controllers\admin\helper;

use App\Http\Controllers\Controller;
use App\Models\admin\sub_categories\subCategoriesModel;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    //this function show sub categories as per category id
    public function getSubCategoriesByCategoryId(Request $request)
    {
        $category_id = $request->category_id;
        $subcategories = subCategoriesModel::where('category_id', $category_id)->where('is_active', true)->get();
        return response()->json($subcategories);
    }
}
