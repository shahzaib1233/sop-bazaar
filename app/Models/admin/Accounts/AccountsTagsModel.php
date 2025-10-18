<?php

namespace App\Models\admin\accounts;

use Illuminate\Database\Eloquent\Model;

class AccountsTagsModel extends Model
{
    //
    protected $table = 'tags_accounts';
    protected $fillable = ['account_id', 'tag'];

    public function account()
    {
        return $this->belongsTo(AccountModel::class, 'account_id');
    }
}
