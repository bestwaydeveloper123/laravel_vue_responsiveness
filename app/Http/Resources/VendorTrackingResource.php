<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorTrackingResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'refunded' => $this->refunded,
      'spoke_to' => $this->spoke_to,
      'confirmation' => $this->confirmation,
      'label_creation_date' => $this->label_creation_date,
    ];
  }
}
