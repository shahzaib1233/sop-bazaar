<?php

namespace App\Models\admin\categories;

use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    protected $table = 'category';

    protected $fillable = ['name', 'slug', 'description', 'status'];

    public $timestamps = true;
}
