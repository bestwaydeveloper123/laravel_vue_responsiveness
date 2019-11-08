<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RmaForms extends Model
{
    public function account()
    {
        return $this->hasOne('App\Account', 'id', 'account_id');
    }
}
