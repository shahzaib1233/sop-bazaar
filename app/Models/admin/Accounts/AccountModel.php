<?php

namespace App\Models\admin\Accounts;

use App\Models\admin\Accounts\AccountStatus\AccountStatusesModel;
use App\Models\admin\categories\CategoriesModel;
use App\Models\admin\sub_categories\subCategoriesModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AccountModel extends Model
{
    protected $table = 'accounts';
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'subcategory_id',
        'price',
        'status_id',
        'description',
        'is_active',
        'tags',
        'created_by',
        'ip_address',
        'geoip_data',
    ];

    // protected $casts = [
    //     'tags' => 'array',
    // ];
    public function category()
    {
        return $this->belongsTo(CategoriesModel::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(AccountsImageModel::class, 'account_id');
    }

    public function tags()
    {
        return $this->hasMany(AccountsTagsModel::class, 'account_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(subCategoriesModel::class, 'subcategory_id');
    }

    public function status()
    {
        return $this->belongsTo(AccountStatusesModel::class, 'status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
