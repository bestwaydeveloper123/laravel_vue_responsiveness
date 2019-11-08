<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatCustomerInfo extends Model
{
  protected $fillable = ['customer_id', 'department', 'name', 'email', 'phone', 'question', 'vin', 'status'];
}
