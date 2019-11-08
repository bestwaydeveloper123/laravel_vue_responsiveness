<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
  // protected $guarded = ['id'];
  protected $table = 'order_statuses';
  protected $primaryKey = 'id';

  protected $fillable = [
    'account_id',
    'role_name',
    'orderstatus',
    'orderstatuscustom',
    'team',
    'created_by'
  ];

  public function account()
  {
    return $this->belongsTo('App\Account');
  }
}
