@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
  <div class="main">
    @foreach($accounts as $accountId)
    @php
      $account = DB::table('accounts')->find($accountId); 
    @endphp
    @if($account)
      @php
        $account = DB::table('accounts')->find($account->id);
        $customermetadata = DB::table('customer_meta_datas')->where('account_id', $account->id)->first();
        $vendors = DB::table('vendors')->where('account_id', $account->id)->get();
        $accountnotes = DB::table('account_notes')->where('account_id', $account->id)->get();
      @endphp 
      <p style="page-break-before: always"></p>
      <table class="table table-sm table-striped hide hide-on-screen mb-1 @if($account->onedayhandling == 1) text-danger @endif" style="width:100%">
        <tr>
          <td colspan="7" class="m-0 p-0">
            <h2 class="m-0 p-0">{{ $account->accountname }}@if($account->onedayhandling == 1)!!!@endif</h2>
          </td>
        </tr>
        <tr>
          <td width="25%" class="m-0 p-0">
            <h2 class="m-0 p-0">H{{ $account->id}}</h2>
          </td>
          <td></td>
          <td width="25%" class="m-0 p-0">
            <h2 class="m-0 p-0">@php if(isset($barcode)) echo $barcode @endphp</h2>
          </td>
          <td></td>
          <td width="50%" class="m-0 p-0">
            <h2 class="m-0 p-0">{{ date('Y-m-d H:i:s') }}</h2>
          </td>
        </tr>
      </table>

      <table class="table table-sm table-striped hide hide-on-screen" style="width:100%">
        <tr>
          <td colspan="4">
            <ul class="nav nav-list">
              @if($account->ordertype == "Phone - Pickup")
              <li>
                <div class="theme-color text-light mr-3"><i style="font-size: 30px;padding: 5px;margin: 4px;" class="fa fa-phone fa-3x text-light bg-red"></i></div>
              </li>
              @endif

              @if($customermetadata->level == "Plus (2+ sales)")
              <li>
                <div class="theme-color text-light mr-3">
                  <p class="btn btn-primary btn-md" style="padding: 5px;margin: 4px 0px 4px 4px;font-weight: bold;font-size: 20px;border-radius: unset;width: 42px;">P1</p>
                </div>
              </li>
              @endif

              @if($customermetadata->level == "Premier (3+ sales)")
              <li>
                <div>
                  <p class="btn btn-primary btn-md" style="padding: 5px;margin: 4px 15px 4px 4px;font-weight: bold;font-size: 20px;border-radius: unset;width: 42px;">P2</p>
                </div>
              </li>
              @endif

              @if($customermetadata->level == "Platinum (5+ sales)")
              <li>
                <div>
                  <p class="btn btn-primary btn-md" style="padding: 5px;margin: 4px 15px 4px 4px;font-weight: bold;font-size: 20px;border-radius: unset;width: 42px;">P3</p>
                </div>
              </li>
              @endif

              @if($account->itempurchased != '')
              @php $itempurchased = substr($account->itempurchased, 1) @endphp
              @if ( strpos($itempurchased, "Keys") !== FALSE )
              <li>
                <div class="theme-color text-light mr-3"><i style="font-size: 30px;padding: 5px;margin: 4px;" class="fa fa-key fa-3x bg-red"></i></div>
              </li>
              @endif
              @endif

              @if ($account->onedayhandling || $account->sticker || $account->fixplugorcase
              || $account->changelabel || $account->removebracket || $account->wrongpart || $account->prog_mods)
              <li>
                <div class="theme-color text-light mr-3">
                  <i class="fa fa-check-square fa-3x text-primary" title="{{
                    ($account->onedayhandling ? "1 day handling\r\n" : '')
                    . ($account->sticker ? "sticker\r\n" : '')
                    . ($account->fixplugorcase ? "fix plug/case\r\n" : '')
                    . ($account->changelabel ? "change label\r\n" : '')
                    . ($account->removebracket ? "remove bracket\r\n" : '')
                    . ($account->wrongpart ? "wrong part\r\n" : '')
                    . ($account->prog_mods ? "programming mod" : '')
                  }}"></i>
                </div>
              </li>
              @endif

              @if ( isset($account->programmingdetails) )
              <li>
                <div class="theme-color text-light mr-3">
                  <i class="fa fa-info-circle fa-3x theme-color text-info" title="{{ $account->programmingdetails }}"></i>
                </div>
              </li>
              @endif
            </ul>
          </td>
        </tr>
        <tr>
          <td colspan="4">
            <h3>Account Details</h3>
          </td>
        </tr>
        <tr>
          <td width="25%">Date Purchased</td>
          <td width="25%">{{ $account->datecustomerpurchased }}</td>
          <td width="25%">Account Owner</td>
          <td width="25%">{{ $account->accountteam }}</td>
        </tr>
        <tr>
          <td>Type Of Order</td>
          <td>{{ $account->ordertype }}</td>
          <td>Account Number</td>
          <td>{{ $account->id }}</td>
        </tr>
        <tr>
          <td>Account Name</td>
          <td>{{ $account->accountname }}</td>
          <td>Primary Account Manager</td>
          <td>{{ $account->primaryaccountmanager }}</td>
        </tr>
        <tr>
          <td>Ebay Username</td>
          <td>{{ $account->ebayusername }}</td>
          <td>Secondary Account Manager</td>
          <td>{{ $account->secondaryaccountmanager }}</td>
        </tr>
        <tr>
          <td>Phone Number</td>
          <td>{{ $customermetadata->phone }}</td>
          <td>Keys</td>
          <td align="left">
            @if($account->itempurchased != '')
            @if ( strpos(strtolower($account->itempurchased), "keys") )
            <input type="checkbox" class="form-control checkbox-height" checked>
            @else
            <input type="checkbox" class="form-control checkbox-height">
            @endif
            @endif
          </td>
        </tr>
        <tr>
          <td>Item Purchased</td>
          <td>{{ $account->itempurchased }}</td>
          <td>1 Day handling</td>
          <td>
            @if($account->onedayhandling == 1)
            <input type="checkbox" class="form-control checkbox-height" checked>
            @else
            <input type="checkbox" class="form-control checkbox-height">
            @endif
          </td>
        </tr>
        <tr>
          <td>Customer VIN</td>
          <td>{{ $account->customervin }}</td>
          <td>Sticker</td>
          <td>
            @if($account->sticker == 1)
            <input type="checkbox" class="form-control checkbox-height" checked>
            @else
            <input type="checkbox" class="form-control checkbox-height">
            @endif
          </td>
        </tr>
        <tr>
          <td>PCM Hardware Type</td>
          <td>{{ $account->pcmhardwaretype }}</td>
          <td>Fix Plug Or Case</td>
          <td>
            @if($account->fixplugorcase == 1)
            <input type="checkbox" class="form-control checkbox-height" checked>
            @else
            <input type="checkbox" class="form-control checkbox-height">
            @endif
          </td>
        </tr>
        <tr>
          <td>Computer Type</td>
          <td>{{ $account->computertype }}</td>
          <td>Change Label(for shipping dept)</td>
          <td>
            @if($account->changelabel == 1)
            <input type="checkbox" class="form-control checkbox-height" checked>
            @else
            <input type="checkbox" class="form-control checkbox-height">
            @endif
          </td>
        </tr>
        <tr>
          <td>Programming Details</td>
          <td>{{ $account->programmingdetails }}</td>
          <td>Remove bracket</td>
          <td>
            @if($account->removebracket == 1)
            <input type="checkbox" class="form-control checkbox-height" checked>
            @else
            <input type="checkbox" class="form-control checkbox-height">
            @endif
          </td>
        </tr>
        <tr>
          <td>Type Of Order</td>
          <td>{{ $account->ordertype }}</td>
          <td>Wrong Part</td>
          <td>
            @if($account->wrongpart == 1)
            <input type="checkbox" class="form-control checkbox-height" checked>
            @else
            <input type="checkbox" class="form-control checkbox-height">
            @endif
          </td>
        </tr>
        <tr>
          <td>Date Purchased</td>
          <td>{{ $account->datecustomerpurchased }}</td>
          <td>Has item Shipped To Customer?</td>
          <td></td>
        </tr>
        <tr>
          <td>Required Delivery Date</td>
          <td>{{ $account->requireddeliverydate }}</td>
          <td>SKU Price</td>
          <td>{{ $account->skuprice }}</td>
        </tr>
        <tr>
          <td>Customer Email</td>
          <td>{{ $customermetadata->email }}</td>
          <td>Price Paid</td>
          <td>{{ $account->pricepaid }}</td>
        </tr>
        <tr>
          <td>Contest Multiplier</td>
          <td>{{ $account->contestmultiplier }}</td>
          <td>Who Made this sale</td>
          <td>{{ $account->whomadethesale }}</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td>Secondary Sales Person</td>
          <td>{{ $account->secondarysale }}</td>
        </tr>
      </table>

      <table class="table table-sm table-striped hide hide-on-screen" style="width:100%">
        <tr>
          <td colspan="6">
            <h3>Vendors</h3>
          </td>
        </tr>

        @foreach(json_decode($vendors) as $vendor)
        <tr>
          <td width="25%">Vendor Purchased From</td>
          <td width="25%">{{ $vendor->purchasedfrom }}</td>
          <td width="25%">Date Purchased</td>
          <td width="25%">{{ $vendor->datepurchased }}</td>
        </tr>
        <tr>
          <td>Vendor Price FS Paid for Part</td>
          <td>{{ $vendor->partprice }}</td>
          <td>Salesperson Stock#</td>
          <td>{{ $vendor->salespersonstockno }}</td>
        </tr>
        <tr>
          <td>Delivery Date To FS1</td>
          <td>{{ $vendor->deliverydate }}</td>
          <td>Vendor Price FS Paid for Shipping</td>
          <td>{{ $vendor->shippingprice }}</td>
        </tr>
        <tr>
          <td>VIN From Donor Vehicle</td>
          <td>{{ $vendor->vin }}</td>
          <td>Tracking #</td>
          <td>{{ $vendor->trackingnumber }}</td>
        </tr>
        <tr>
          <td>Pickup Charge</td>
          <td>{{ $vendor->pickupcharge }}</td>
          <td></td>
          <td></td>
        </tr>
        @endforeach
      </table>

      <table class="table table-sm table-striped hide hide-on-screen" style="width:100%">
        <tr>
          <td colspan="3">
            <h3>Account Notes</h3>
          </td>
          <td></td>
        </tr>
        @foreach($accountnotes as $accountnote)
        <tr>
          <td width="25%">{{ $accountnote->username }}</td>
          <td width="25%">{{ $accountnote->created_at }}</td>
          {{-- <td width="50%">@php echo $accountnote->notes @endphp</td> --}}
          <td></td>
        </tr>
        @endforeach
      </table>
    @endif 
    @endforeach
  </div>
</div>
</div>
@stop

@push('user_index')
<script>
  $(document).ready(function() {
    window.print();
  });
</script>
@endpush