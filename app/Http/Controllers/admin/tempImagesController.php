<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\tempImageModel;
use Illuminate\Http\Request;

class tempImagesController extends Controller
{
    //

    public function create(Request $request)
    {
        $image = $request->file('image');
        if(empty($image)){
            return response()->json([
                'status' => 'error',
                'message' => 'No image uploaded.',
            ], 400);
        }

        $ext = $image->getClientOriginalExtension();
        $newName = 'temp_' . time() . '.' . $ext;
        $tempImage = new tempImageModel();
        $tempImage->name = $newName;
        $tempImage->save();

        $image->move(public_path('uploads/temp'), $newName);
        return response()->json([
            'status' => 'true',
            'image_id' => $tempImage->id,
            'message' => 'Image uploaded successfully.',
            'image_url' => asset('uploads/temp/' . $newName),
        ]);
    }
}
