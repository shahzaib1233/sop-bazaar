<?php

namespace App\Models\admin\Accounts\AccountStatus;

use Illuminate\Database\Eloquent\Model;

class AccountStatusesModel extends Model
{
    //
    protected $table = 'status';
    protected $fillable = ['name', 'description', 'slug'];
    
}
