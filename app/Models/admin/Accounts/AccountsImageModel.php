<?php

namespace App\Models\admin\Accounts;

use Illuminate\Database\Eloquent\Model;

class AccountsImageModel extends Model
{
    protected $table = 'accounts_images';
    protected $fillable = [
        'account_id',
        'image',
        'is_primary',
    ];

        public function account()
    {
        return $this->belongsTo(AccountModel::class, 'account_id');
    }
}
