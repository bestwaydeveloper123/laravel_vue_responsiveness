<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingTeamLeaderPoint extends Model
{
    protected $fillable = ['account_id', 'tracking_status_id', 'username'];
}
