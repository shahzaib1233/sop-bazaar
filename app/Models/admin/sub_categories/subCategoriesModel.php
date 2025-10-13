<?php

namespace App\Models\admin\sub_categories;

use App\Models\admin\categories\CategoriesModel;
use Illuminate\Database\Eloquent\Model;

class subCategoriesModel extends Model
{
    //
    protected $table = 'sub_categories';
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'is_active',
        'image',
    ];
    public function category()
    {
        return $this->belongsTo(CategoriesModel::class, 'category_id');
    }

    
}
