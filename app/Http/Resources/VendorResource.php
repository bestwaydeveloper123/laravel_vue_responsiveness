<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'purchasedfrom' => $this->purchasedfrom,
      'salespersonstockno' => $this->salespersonstockno,
      'vin' => $this->vin,
      'in_stock' => $this->in_stock,
      'pricepaid' => $this->pricepaid,
      'partprice' => $this->partprice,
      'shippingprice' => $this->shippingprice,
      'pickupcharge' => $this->pickupcharge,
      'datepurchased' => $this->datepurchased,
      'deliverydate' => $this->deliverydate,
      'trackingnumber' => $this->trackingnumber,
      'shippinglabel' => $this->shippinglabel,
      'creditcard' => $this->creditcard,
      'tracking' => new VendorTrackingResource($this->tracking),
      'junkyardAddress' => new JunkyardAddressResource($this->junkyardAddress),
    ];
  }
}
