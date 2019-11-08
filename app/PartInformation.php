<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartInformation extends Model
{
  protected $table = 'part_information';
  protected $fillable = [
    'item_purchased', 'pcm_hw', 'computer_type', 'price_paid'
  ];
}
