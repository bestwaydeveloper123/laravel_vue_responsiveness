<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JunkyardAddress extends Model
{
  protected $fillable = ['vendor_id', 'shopname', 'street1', 'street2', 'city', 'state', 'zipcode'];
}
