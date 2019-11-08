<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackingStatus extends Model
{
    protected $guarded = ['id'];
  
    public function EasypostWebhook()
    {
      return $this->hasOne('App\EasypostWebhook', 'trackingcode', 'trackingcode');
    }
}
