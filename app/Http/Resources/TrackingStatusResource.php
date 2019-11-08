<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackingStatusResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'direction' => $this->direction,
      'dateshippedtocustomer' => $this->dateshippedtocustomer,
      'deliverydatetocustomer' => $this->deliverydatetocustomer,
      'antitheft' => $this->antitheft,
      'customertrackingnumber' => $this->customertrackingnumber,
      'returntrackinginfo' => $this->returntrackinginfo,
      'warrantysticker' => $this->warrantysticker,
      'rma' => $this->rma,
      'warrantyexhausted' => $this->warrantyexhausted,
      'returnShippingDate' => $this->returnShippingDate,
      'unauthorized' => $this->unauthorized,
      'customerProvideTracking' => $this->customerProvideTracking,
      'returnShippingCost' => $this->returnShippingCost,
      'shippinglabel' => $this->shippinglabel,
      'trackingcode' => $this->trackingcode,
      'realtimetracking' => $this->realtimetracking
    ];
  }
}
