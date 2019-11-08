<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
  protected $guarded = ['id'];
  protected $fillable = [
    'account_id', 'purchasedfrom', 'salespersonstockno', 'in_stock', 'returnreason',
    'vin', 'pricepaid', 'partprice', 'shippingprice', 'pickupcharge', 'datepurchased',
    'deliverydate', 'trackingnumber', 'shippinglabel', 'creditcard', 'trackingcode', 'packagetype',
    'rate', 'carrier', 'label_creation_date', 'refunded'
  ];

  public function account()
  {
    return $this->belongsTo('App\Account');
  }

  public function tracking()
  {
    return $this->hasOne('App\VendorTracking');
  }

  public function EasypostWebhook()
  {
    return $this->hasOne('App\EasypostWebhook', 'trackingcode', 'trackingcode');
  }

  public function junkyardAddress()
  {
    return $this->hasOne('App\JunkyardAddress');
  }

  public function addJunkyardAddress($address, $update = false)
  {
    if ($update) {
      $this->junkyardAddress()->update($address);
    } else {
      $this->junkyardAddress()->create($address);
    }
  }
}
