<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAppOption extends Model
{ 
    protected $fillable = [
        'user_id', 'account_id', 'inventory_id', 'app_option_id', 'created_by'
    ];
    
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function AppOption()
    {
        return $this->hasOne('App\AppOption', 'id', 'app_option_id');
    }
}
