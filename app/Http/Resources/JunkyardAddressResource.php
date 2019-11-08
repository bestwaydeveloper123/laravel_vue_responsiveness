<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JunkyardAddressResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'shopname' => $this->shopname,
      'street1' => $this->street1,
      'street2' => $this->street2,
      'city' => $this->city,
      'state' => $this->state,
      'zipcode' => $this->zipcode,
    ];
  }
}
