<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockInformation extends Model
{
    public function StockMaster()
    {
        return $this->hasOne('App\StockMaster', 'id', 'stock_master_id');
    }
}
