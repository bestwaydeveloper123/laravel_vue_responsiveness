<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgrammingEntryResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id'                   => $this->id,
      'entrytype'            => $this->entrytype,
      'salespersonstockno'   => $this->salespersonstockno,
      'frdkeys'              => $this->frdkeys,
      'chyskimreset'         => $this->chyskimreset,
      'chydonorinfo'         => $this->chydonorinfo,
      'chydonorinfo2'        => $this->chydonorinfo2,
      'chycustinfo'          => $this->chycustinfo,
      'chycustinfo2'         => $this->chycustinfo2,
      'general'              => $this->general,
      'doa'                  => $this->doa,
      'wronghw'              => $this->wronghw,
      'wrongecuhw'           => $this->wrongecuhw,
      'wrongparttype'        => $this->wrongparttype,
      'purchaseinfomismatch' => $this->purchaseinfomismatch,
      'securitymismatch'     => $this->securitymismatch,
      'badlydamagedunit'     => $this->badlydamagedunit,
      'needsw'               => $this->needsw,
      'needswpn'             => $this->needswpn,
      'needcustvin'          => $this->needcustvin,
      'vendorsentincorrect'  => $this->vendorsentincorrect,
      'needonboardtesting'   => $this->needonboardtesting,
      'description'          => $this->description,
      'correcthwneeded'      => $this->correcthwneeded,
      'programmingnotes'     => $this->programmingnotes,
      'username'             => $this->username,
    ];
  }
}
