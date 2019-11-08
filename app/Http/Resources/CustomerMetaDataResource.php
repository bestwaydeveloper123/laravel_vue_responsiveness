<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerMetaDataResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'shipto'       => $this->shipto,
      'street1'      => $this->street1,
      'street2'      => $this->street2,
      'city'         => $this->city,
      'state'        => $this->state,
      'zip'          => $this->zip,
      'country'      => $this->country,
      'phone'        => $this->phone,
      'phone2'       => $this->phone2,
      'email'        => $this->email,
      'email2'       => $this->email2,
      'level'        => $this->level,
      'shopname'     => $this->shopname,
    ];
  }
}
