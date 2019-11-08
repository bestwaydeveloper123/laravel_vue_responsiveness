<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScannerHistorys extends Model
{
    
    protected $fillable = [
        'inventories_id', 'username', 'action' 
    ];
}
