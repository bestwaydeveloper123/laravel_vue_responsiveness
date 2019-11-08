@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
  <div class="main" id="vue-app">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
      Please correct following errors:
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

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
            @if ( $account->ordertype == "Phone - Pickup" )
            <li>
              <div class="theme-color text-light mr-3"><i style="font-size: 30px;padding: 5px;margin: 4px;" class="fa fa-phone fa-3x text-light bg-red"></i></div>
            </li>
            @endif

            @if (isset($customermetadata->level) && $customermetadata->level == "Plus (2+ sales)")
            <li>
              <div class="theme-color text-light mr-3">
                <p class="btn btn-primary btn-md" style="padding: 5px;margin: 4px 0px 4px 4px;font-weight: bold;font-size: 20px;border-radius: unset;width: 42px;">P1</p>
              </div>
            </li>
            @endif

            @if (isset($customermetadata->level) && $customermetadata->level == "Premier (3+ sales)")
            <li>
              <div>
                <p class="btn btn-primary btn-md" style="padding: 5px;margin: 4px 15px 4px 4px;font-weight: bold;font-size: 20px;border-radius: unset;width: 42px;">P2</p>
              </div>
            </li>
            @endif

            @if (isset($customermetadata->level) && $customermetadata->level == "Platinum (5+ sales)")
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
        @if (isset($customermetadata->phone))
        <td>{{ $customermetadata->phone }}</td>
        @else
        <td></td>
        @endif
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
        @if (isset($customermetadata->email))
        <td>{{ $customermetadata->email }}</td>
        @else
        <td></td>
        @endif
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

    <form method="POST" action="/accounts/{{ $account->id }}" enctype="multipart/form-data" class="noPrint">
      @include('admin.accounts.misc.breadcrumbs', ['saveId' => 'account-save'])
      @csrf
      @method('PATCH')
      {{-- <input type="hidden" name="modified_by" value="{{ auth()->id() }}"> --}}
      @if(intval(auth()->user()->role == 4))
      @include('admin.accounts.misc.shipping')
      @endif

      <customer-information user-role="{{ auth()->user()->role }}" account-data="{{ $account }}" customer-data="{{ json_encode($customermetadata) }}" account-users="{{ $accountusers }}" tracking-number="{{ $customerTrackingNumber }}" is-point="{{ $IsPoints }}" shipped-date="{{ $shippedDate }}"></customer-information>

      <order-information user-role="{{ auth()->user()->role }}" account-data="{{ $account }}" customer-data="{{ json_encode($customermetadata) }}" account-users="{{ $accountusers }}" stock-information="{{ $StockInformation }}" role-name="{{ $rolename }}" order-statuses="{{ $orderstatuses }}" user-name="{{ auth()->user()->username }}" is-edit></order-information>
      <!-- <div class="card mb-1">
        <div class="card-header card-header pt-1 pb-1 bg-gray-700 theme-color">
          <a class="text-light" data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion2" aria-expanded="true" aria-controls="exampleAccordion2">
            <strong><i class="fa fa-edit"></i> Order Information <i class="fa fa-caret-down"></i></strong>
          </a>
        </div>
        <div class="card-body pt-0 pb-0">
          <div id="exampleAccordion" data-children=".item">
            <div class="item">
              <div class="collapse {{ intval(auth()->user()->role) == 3 || intval(auth()->user()->role) == 4 ? '' : 'show' }} table-responsive" id="exampleAccordion2" role="tabpanel">
                <table id="account_table" class="table table-responsive-sm table-sm mb-1" width="100%">
                  <thead></thead>
                  <tbody>
                    <tr>
                      <td class="pt-0 pb-0" width="11%">
                        <label class="col-form-label col-form-label-sm" for="date-purchased-ctrl">Date Purchased</label>
                      </td>
                      <td class="pt-0 pb-0" width="22%">
                        <span style="float:left;width:auto;"><input class="form-control form-control-sm" id="date-purchased-ctrl" type="date" name="datecustomerpurchased" value="{{ $account->datecustomerpurchased }}"></span><span class="btn btn-sm badge-success ml-2 pt-0 pb-0" id="purtodayDate"><i class="fa fa-calendar-check-o"></i></span>
                      </td>
                      <td class="pt-0 pb-0" width="11%">
                        <label class="col-form-label col-form-label-sm" for="text-input">VIN</label>
                      </td>
                      <td class="pt-0 pb-0" width="22%">
                        <div class="input-group">
                          <textarea class="form-control form-control-sm" onkeyup="vinValueChange2();" id="customervin2" type="text" name="customervin" placeholder="" rows="1">{{ $account->customervin }}</textarea>
                          <span class="input-group-append d-none" id="customervinicon">
                            <button class="btn btn-sm btn-secondary text-light bg-success" type="button"><i class="fa fa-check"></i></button>
                          </span>
                        </div>
                      </td>
                      <td class="pt-0 pb-0" width="11%"><label class="col-form-label col-form-label-sm" for="text-input">Ebay Username</label></td>
                      <td class="pt-0 pb-0" width="22%">
                        <div class="input-group">
                          <input class="form-control form-control-sm" id="ebayusername" type="text" name="ebayusername" placeholder="" value="{{ $account->ebayusername }}">
                          <span class="input-group-append" id="ebayusernamebutton">
                            <a href="https://www.ebay.com/usr/{{ $account->ebayusername }}">
                              <button class="btn btn-sm btn-success text-light" type="button">Msg!</button>
                            </a>
                          </span>
                          <span class="input-group-append" id="ebayusernamebuttontab">
                            <a href="https://www.ebay.com/usr/{{ $account->ebayusername }}" target="_blank">
                              <button class="btn btn-sm btn-primary text-light ml-1" type="button">Tab</button>
                            </a>
                          </span>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="text-input">Required DelivDay</label></td>
                      <td class="pt-0 pb-0"><span style="float:left;width:auto;"><input class="form-control form-control-sm" id="requireddeliverydate" type="date" name="requireddeliverydate" placeholder="" value="{{ $account->requireddeliverydate }}"></span><span class="btn btn-sm badge-success ml-2 pt-0 pb-0" id="deltodayDate"><i class="fa fa-calendar-check-o"></i></span></td>
                      <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="contestmultiplier">Contest Multiplier</label></td>
                      <td class="pt-0 pb-0">
                        <select class="form-control form-control-sm" id="contestmultiplier" type="text" name="contestmultiplier">
                          <option value="{{ $account->contestmultiplier }}">{{ $account->contestmultiplier }}</option>
                          <option value="1">x1</option>
                          <option value="2">x2</option>
                          <option value="3">x3</option>
                          <option value="4">x4</option>
                          <option value="5">x5</option>
                          <option value="6">x6</option>
                        </select>
                      </td>
                      <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="text-input">Sales Record</label></td>
                      <td class="pt-0 pb-0"><input class="form-control form-control-sm" id="salesrecord" type="text" name="salesrecord" placeholder="" value="{{ $account->salesrecord }}"></td>
                    </tr>
                    <tr>
                      <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm">Order Type</label></td>
                      <td class="pt-0 pb-0">
                        <select class="form-control form-control-sm" name="ordertype" id="order-type-ctrl2">
                          @if ( isset($account->ordertype) )
                          <option value="{{ $account->ordertype }}">{{ $account->ordertype }}</option>
                          @else
                          <option value="">--None Selected--</option>
                          @endif
                          <option value="eBay- fs1inc">eBay- fs1inc</option>
                          <option value="Website">Website</option>
                          <option value="Phone">Phone</option>
                          <option value="Quote">Quote</option>
                          <option value="Stock">Stock</option>
                          <option value="Lead">Lead</option>
                          <option value="Paypal">Paypal</option>
                          <option value="eBay - other Store">eBay - other Store</option>
                          <option value="Phone - Pickup">Phone - Pickup</option>
                        </select>
                      </td>
                      <td class="pt-0 pb-0">
                        <label class="col-form-label col-form-label-sm" for="docusign">Docusign</label>
                      </td>
                      <td class="pt-0 pb-0">
                        <select id="docusign" class="form-control form-control-sm" name="docusign">
                          @if(isset($account->docusign))
                          <option value="{{ $account->docusign }}">{{ $account->docusign }}</option>
                          @endif
                          <option value="">-- None --</option>
                          <option value="Docusign sent but not signed">Docusign sent but not signed</option>
                          <option value="Signed original purchase receipt">Signed original purchase receipt</option>
                          <option value="Signed return RMA form">Signed return RMA form</option>
                          <option value="Signed both">Signed both</option>
                          <option value="Other(put information in notes)">Other(put information in notes)</option>
                          <option value="Docusign not sent yet">Docusign not sent yet</option>
                        </select>
                      </td>
                      <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="magento_id">Magento ID</label></td>
                      <td class="pt-0 pb-0"><input class="form-control form-control-sm" id="magento_id" type="text" name="magento_id" value="{{ $account->magento_id }}"></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="ebayorder_id">Ebay ID</label></td>
                      <td class="pt-0 pb-0"><input class="form-control form-control-sm" id="ebayorder_id" type="text" name="ebayorder_id" value="{{ $account->ebayorder_id }}"></td>
                    </tr>
                  </tbody>
                </table>

                <stock-information stock-info="{{ $StockInformation }}" order-type="{{ $account->ordertype }}"></stock-information>
                <attribute-card :account="{{ json_encode($account) }}" :customer="{{ json_encode($customermetadata) }}"></attribute-card>

                <div class="row">
                  <div class="col-sm-6 col-md-8 pull-left pl-0 pr-0">
                    <div class="col-sm-12 col-md-12 pull-left">
                      <part-information customer-vin="{{ $account->customervin }}" role="{{ auth()->user()->role }}" account-id="{{ $account->id }}" on-board-value="{{ $account->onboard_testing }}" item="{{ $account->itempurchased }}" pcm="{{ $account->pcmhardwaretype }}" com="{{ $account->computertype }}" details="{{ $account->programmingdetails }}" price="{{ $account->pricepaid }}" />
                    </div>
                    <div class="col-sm-12 col-md-12 pull-left">
                      <order-status role-name="{{ $rolename }}" :data="{{ $orderstatuses }}" account-team="{{ $account->accountteam }}" :account-id="{{ $account->id }}" user-name="{{ auth()->user()->username }}" is-edit />
                    </div>
                  </div>
                  <div class="col-sm-6 col-md-4 pull-left">
                    <div class="card mb-1">
                      <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light"><strong><i class="fa fa-user"></i> FS1 Sales People</strong>
                      </div>
                      <div class="card-body pt-0 pb-0 pr-0 pl-0">
                        <table class="table table-responsive-sm table-sm pull-left mb-0">
                          <tr>
                            <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="whomadethesale">Who Made Sale</label></td>
                            <td class="pt-0 pb-0">
                              <select class="form-control form-control-sm" name="whomadethesale" id="whomadethesale">
                                @if($account->whomadethesale)
                                <option value="{{ $account->whomadethesale }}">{{ $account->whomadethesale }}</option>
                                @endif
                                <option value="">-- None --</option>
                                @foreach($accountusers as $user)
                                <option value="{{ $user->username }}">{{ $user->username }} - {{ $user->email }}</option>
                                @endforeach
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="secondarysale">Secondary Sale</label></td>
                            <td class="pt-0 pb-0">
                              <select class="form-control form-control-sm" id="secondarysale" name="secondarysale">
                                @if($account->secondarysale)
                                <option value="{{ $account->secondarysale }}">{{ $account->secondarysale }}</option>
                                @endif
                                <option value="">-- None --</option>
                                @foreach($accountusers as $user)
                                <option value="{{ $user->username }}">{{ $user->username }} - {{ $user->email }}</option>
                                @endforeach
                              </select>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div class="card mb-1">
                      <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light"><strong><i class="fa fa-wrench"></i> Options</strong>
                      </div>
                      <div class="card-body pt-0 pb-1">
                        <table>
                          <thead></thead>
                          <tbody>
                            <tr>
                              <td>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                                  <input class="switch-input" type="hidden" name="onedayhandling" value="0">
                                  <input class="switch-input" type="checkbox" id="onedayhandling-1" name="onedayhandling" value="1" {{ ( $account['onedayhandling']=='1' ? 'checked' : '') }}>
                                  <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                                </label>
                              </td>
                              <td class="pb-0 pt-0">1 Day Handling</td>
                            </tr>

                            <tr>
                              <td>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                                  <input class="switch-input" type="hidden" name="sticker" value="0">
                                  <input class="switch-input" type="checkbox" id="sticker-1" name="sticker" value="1" {{ ( $account['sticker']=='1' ? 'checked' : '') }}>
                                  <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                                </label>
                              </td>
                              <td>Sticker</td>
                            </tr>
                            <tr>
                              <td>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                                  <input class="switch-input" type="hidden" name="fixplugorcase" value="0">
                                  <input class="switch-input" type="checkbox" id="fixplugorcase-1" name="fixplugorcase" value="1" {{ ( $account['fixplugorcase']=='1' ? 'checked' : '') }}>
                                  <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                                </label>
                              </td>
                              <td>Fix Plug/Case</td>
                            </tr>
                            <tr>
                              <td>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                                  <input class="switch-input" type="hidden" name="changelabel" value="0">
                                  <input class="switch-input" type="checkbox" id="changelabel-1" name="changelabel" value="1" {{ ( $account['changelabel']=='1' ? 'checked' : '') }}>
                                  <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                                </label>
                              </td>
                              <td>Change Label</td>
                            </tr>
                            <tr>
                              <td>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                                  <input class="switch-input" type="hidden" name="removebracket" value="0">
                                  <input class="switch-input" type="checkbox" id="removebracket-1" name="removebracket" value="1" {{ ( $account['removebracket']=='1' ? 'checked' : '') }}>
                                  <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                                </label>
                              </td>
                              <td>Remove Bracket</td>
                            </tr>
                            <tr>
                              <td>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                                  <input class="switch-input" type="hidden" name="wrongpart" value="0">
                                  <input class="switch-input" type="checkbox" id="wrongpart-1" name="wrongpart" value="1" {{ ( $account['wrongpart']=='1' ? 'checked' : '') }}>
                                  <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                                </label>
                              </td>
                              <td>Wrong Part</td>
                            </tr>
                            <tr>
                              <td>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                                  <input class="switch-input" type="hidden" name="prog_mods" value="0">
                                  <input class="switch-input" type="checkbox" id="progmods-1" name="prog_mods" value="1" {{ ( $account['prog_mods']=='1' ? 'checked' : '') }}>
                                  <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                                </label>
                              </td>
                              <td>Prog Mods</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->

      <vendor-edit account-id="{{ $account->id }}" price-paid="{{ $account->pricepaid }}" :customer-data="{{ json_encode($customermetadata) }}" vendors="{{ $vendors }}" users="{{ $accountusers }}" credit-card="{{ auth()->user()->creditcard }}" role="{{ auth()->user()->role }}"></vendor-edit>

      <programming-information user-role="{{ auth()->user()->role }}" account-data="{{ $account }}" customer-data="{{ json_encode($customermetadata) }}" role-name="{{ $rolename }}" user-name="{{ auth()->user()->username }}" pg-entries="{{ $programmingentries }}" is-edit></programming-information>
      <!-- <div class="card mb-1">
        <div class="card-header bg-gray-700 theme-color py-1">
          <a class="text-light" data-toggle="collapse" href="#programmingAccordition" aria-expanded="true" aria-controls="programmingAccordition">
            <strong><i class="fa fa-edit"></i> Programming <i class="fa fa-caret-down"></i></strong>
          </a>
        </div>
        <div class="card-body py-0" data-children=".item">
          <div class="item collapse {{ intval(auth()->user()->role) == 1 || intval(auth()->user()->role) == 3 ? 'show' : '' }}" id="programmingAccordition" role="tabpanel">
            <table class="table table-bordered table-responsive-sm table-sm mb-1" width="100%">
              <tr>
                <td class="pt-0 pb-0 font-weight-700">
                  <label class="col-form-label col-form-label-sm" for="order-type1">{{ $account->id }}</label>
                </td>
                <td class="pt-0 pb-0 font-weight-700">
                  <label class="col-form-label col-form-label-sm" for="order-type1">{{ $account->ordertype }}</label>
                </td>
                <td class="pt-0 pb-0 font-weight-700">
                  <label class="col-form-label col-form-label-sm" for="order-type1">{{ $account->datecustomerpurchased }}</label>
                </td>
                <td class="pt-0 pb-0 font-weight-700">
                  <label class="col-form-label col-form-label-sm" for="order-type1">{{ $account->accountname }}</label>
                </td>
                <td class="pt-0 pb-0 font-weight-700">
                  <label class="col-form-label col-form-label-sm" for="order-type1">{{ $account->primaryaccountmanager }}</label>
                </td>
              </tr>
            </table>
            <table id="account_table" class="table table-bordered table-responsive-sm table-sm mb-1" width="100%">
              <thead></thead>
              <tbody>
                <tr>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm" for="text-input1">Item Purchased</label>
                  </td>
                  <td class="pt-0 pb-0" colspan="4">
                    <textarea class="form-control form-control-sm bg-gray-100" id="text-input1" type="text" rows="1" readonly>{{ $account->itempurchased }}</textarea>
                  </td>
                </tr>
                <tr>
                  <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="text-input">Customer VIN</label></td>
                  <td class="pt-0 pb-0" colspan="2">
                    <div class="input-group">
                      <input class="form-control form-control-sm  bg-gray-100" id="customervin" type="text" readonly value="{{ $account->customervin }}">
                      <span class="input-group-append">
                        <button class="btn btn-sm btn-secondary text-light bg-info" type="button" onclick="var copyText=document.getElementById('customervin'); copyText.select(); document.execCommand('copy');">
                          copy
                        </button>
                      </span>
                    </div>
                  </td>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm" for="hardware">Hardware</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <input onkeyup="changeHardwareValue();" class="form-control form-control-sm bg-gray-100" id="hardware" type="text" value="{{ $account->pcmhardwaretype }}">
                  </td>
                </tr>
                <tr>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm" for="text-input">Part Location</label>
                  </td>
                  <td class="pt-0 pb-0" colspan="2">
                    <div class="input-group">
                      <part-location account-team="{{ $account->accountteam }}" role-name="{{ $rolename }}" :account-id="{{ $account->id }}">
                      </part-location>
                    </div>
                  </td>
                  <td class="pt-0 pb-0"><label class="col-form-label col-form-label-sm" for="prog-notes-2">Prog Notes</label></td>
                  <td class="pt-0 pb-0"><textarea onkeyup="pNoteValueChange2();" class="form-control form-control-sm bg-gray-100" id="prog-notes-2" rows="1">{{ $account->programmingdetails }}</textarea></td>
                </tr>
              </tbody>
            </table>
            <attribute-card :account="{{ json_encode($account) }}" :customer="{{ json_encode($customermetadata) }}"></attribute-card>
            <programming-entry :pg-entries="{{ $programmingentries }}" role="{{ auth()->user()->role }}" account-id="{{ $account->id }}" on-board-value="{{ $account->onboard_testing }}"></programming-entry>
          </div>
        </div>
      </div> -->

      @include('admin.accounts.misc.accountnotes')

      @if(intval(auth()->user()->role != 4))
      @include('admin.accounts.misc.shipping')
      @endif

      <div class="card pb-1 mb-0 noPrint">
        <div class="card-header card-header pt-1 pb-1 bg-gray-700 theme-color">
          <a class="text-light float-left" data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion7" aria-expanded="true" aria-controls="exampleAccordion7">
            <strong><i class="fa fa-edit"></i> Document Attachments<i class="fa fa-caret-down"></i></strong>
          </a>
        </div>
        <div class="card-body pt-0 pb-0">
          <div id="exampleAccordion" data-children=".item">
            <div class="item">
              <div class="collapse {{ intval(auth()->user()->role) == 2 || intval(auth()->user()->role) == 3 ? '' : 'show' }}" id="exampleAccordion7" role="tabpanel">
                <div class="file-upload">
                  <input type="button" class="file-upload-button" value="Add Images" onclick="$('#image-to-upload').click();" />
                  <div class="fileupload-input">
                    <div>Drag and drop a file</div>
                    <input type='file' accept="image/*" name="image[]" id="image-to-upload" multiple onchange="$('#image-count').html(this.files.length)" />
                  </div>
                  <br>
                  <span>Selected Files: </span><span id='image-count'>0</span>
                  <br>
                  <textarea placeholder="Description" name="description"></textarea>
                </div>
                <image-view accounid="{{ $account->id }}" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <meta-info user-role="{{ auth()->user()->role }}" account-data="{{ $account }}" primary-points="{{ $primary_points }}" is-edit />
      <!-- <div class="card pb-1 mb-0">
        <div class="card-header card-header pt-1 pb-1 bg-gray-700 theme-color">
          <a class="text-light float-left" data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion8" aria-expanded="true" aria-controls="exampleAccordion8">
            <strong><i class="fa fa-edit"></i> Meta Info<i class="fa fa-caret-down"></i></strong>
          </a>
        </div>
        <div class="card-body pt-0 pb-0">
          <div id="exampleAccordion" data-children=".item">
            <div class="item">
              <div class="collapse {{ intval(auth()->user()->role) !== 3 && intval(auth()->user()->role) !== 4 ? 'show' : '' }} table-responsive" id="exampleAccordion8" role="tabpanel">
                <table class="table table-sm table-responsive-sm table-bordered mb-0" width="50%">
                  <tr>
                    <td class="pt-0 pb-0" width="11%"><label class="col-form-label col-form-label-sm" for="text-input4">Created By</label></td>
                    <td class="pt-0 pb-0" width="22%"><input class="form-control form-control-sm" id="text-input4" type="text" name="text-input" placeholder="" readonly value="{{ $account->created_by }}"></td>
                    <td class="pt-0 pb-0" width="11%"><label class="col-form-label col-form-label-sm" for="text-input5">Creation Time</label></td>
                    <td class="pt-0 pb-0" width="22%"><input class="form-control form-control-sm" id="text-input5" type="text" name="text-input" placeholder="" readonly value="{{ $account->created_at }}"></td>
                    <td class="pt-0 pb-0" width="11%"><label class="col-form-label col-form-label-sm" for="text-input6">Primary Points</label></td>
                    <td class="pt-0 pb-0" width="22%"><input class="form-control form-control-sm" id="text-input6" type="text" name="text-input" placeholder="" value="{{ $primary_points }}" readonly></td>
                  </tr>
                  <tr>
                    <td class="pt-0 pb-0" style="width: 13%;"><label class="col-form-label col-form-label-sm" for="text-input7">Last Modified By</label></td>
                    <td class="pt-0 pb-0"><input class="form-control form-control-sm" id="text-input7" type="text" name="text-input" placeholder="" readonly value="{{ $account->modified_by }}"></td>
                    <td class="pt-0 pb-0" style="width: 13%;"><label class="col-form-label col-form-label-sm" for="text-input8">Last Modified Time</label></td>
                    <td class="pt-0 pb-0"><input class="form-control form-control-sm" id="text-input8" type="text" name="text-input" placeholder="" readonly value="{{ $account->updated_at }}"></td>
                    <td class="pt-0 pb-0" colspan="2">
                      <meta-info :account-id="{{ $account->id }}" is-edit />
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div> -->

      @include('admin.accounts.misc.breadcrumbs')

    </form>
  </div>
</div>
@stop
@push('account_edit')
<script>
  $('body').on('keydown', 'input, select', function(e) {
    var self = $(this),
      form = self.parents('form:eq(0)'),
      focusable, next;
    if (e.keyCode == 13) {
      // focusable = form.find('input,a,select,button,textarea').filter(':visible');
      // next = focusable.eq(focusable.index(this) + 1);
      // if (next.length) {
      //   next.focus();
      // } else {
      //   form.submit();
      // }
      $(this).closest('tr').next().find('input,a,select,button,textarea').focus();
      return false;
    }
  });
  $(document).ready(function() {
    let tx = document.getElementsByTagName('textarea');
    for (let i = 0; i < tx.length; i++) {
      if (tx[i].scrollHeight > 0) {
        tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight) + 'px;overflow-y:hidden;');
      }
      tx[i].addEventListener("input", OnInput, false);
    }

    function OnInput() {
      this.style.height = 'auto';
      this.style.height = (this.scrollHeight) + 'px';
    }

    let customervin1 = $("#customervin1").val();
    let customervin2 = $("#customervin2").val();
    let customervintest = new RegExp("^[A-Za-z0-9]{8,17}");
    let testresult1 = customervintest.test(customervin1);
    let testresult2 = customervintest.test(customervin2);

    $("#customervin1").keyup(function() {
      let customervin1 = $("#customervin1").val();
      let customervintest1 = new RegExp("^[A-Za-z0-9]{8,17}");
      let testresult1 = customervintest1.test(customervin1);
      if (testresult1) {
        $('#customervinicon').show();
      } else {
        $('#customervinicon').hide();
      }
    });
    $("#customervin2").keyup(function() {
      let customervin2 = $("#customervin2").val();
      let customervintest2 = new RegExp("^[A-Za-z0-9]{8,17}");
      let testresult2 = customervintest2.test(customervin2);
      if (testresult2) {
        $('#customervinicon').show();
      } else {
        $('#customervinicon').hide();
      }
    });

    if (testresult1) {
      $('#customervinicon').show();
    } else {
      $('#customervinicon').hide();
    }
    if (testresult2) {
      $('#customervinicon').show();
    } else {
      $('#customervinicon').hide();
    }
  });

  // customervin programming copy
  $("#customervincopy").click(function() {
    $("#customervin2").select();
    document.execCommand('Copy');
  });

  // email programming copy
  $("#emailcopy").click(function() {
    $("#emailhidden").select();
    document.execCommand('Copy');
  });

  // sync changes
  $(document).ready(function() {
    $('#customer-level-1').change(function() {
      $('#address-edit-card')[0].__vue__.data.level = this.value;
      $('#address-edit-card-1')[0].__vue__.data.level = this.value;
    })

    // $('#order-type-ctrl1').change(function() {
    //   $('#order-type1').val(this.value);
    //   $('#order-type2').val(this.value);
    // })
    $('#order-type-ctrl2').change(function() {
      $('#order-type1').val(this.value);
      $('#order-type2').val(this.value);
    })

    $('#date-purchased-ctrl').change(function() {
      $('#date-purchased').val(this.value);
    })

    const options = [
      'onedayhandling',
      'sticker',
      'fixplugorcase',
      'changelabel',
      'removebracket',
      'wrongpart',
      'progmods'
    ];

    options.forEach((opt) => {
      const p1 = $(`#${opt}-1`);
      const p2 = $(`#${opt}-2`);
      p1.change(function() {
        p2.trigger('click');
      });

      p2.change(function() {
        p1.trigger('click');
      });
    })
  })
</script>

<script>
  $('.print-window').click(function() {
    window.print();
  });
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = now.getFullYear() + "-" + (month) + "-" + (day);

  // $('#date-purchased-ctrl').val(today);

  $("#deltodayDate").click(function() {
    $('#requireddeliverydate').val(today);
  });

  $("#purtodayDate").click(function() {
    $('#date-purchased-ctrl').val(today);
  });

  //VIN and Order Type filed mirror value change

  function vinValueChange1() {
    document.getElementById('customervin2').value = document.getElementById('customervin1').value;
    document.getElementById('customervin').value = document.getElementById('customervin1').value;
    document.getElementById('vin').value = document.getElementById('customervin1').value;
  }

  function vinValueChange2() {
    document.getElementById('customervin1').value = document.getElementById('customervin2').value;
    document.getElementById('customervin').value = document.getElementById('customervin2').value;
    document.getElementById('vin').value = document.getElementById('customervin2').value;
  }

  // function orderTypeValueChange2() {
  //   document.getElementById('order-type-ctrl2').value = document.getElementById('order-type-ctrl2').value;
  // }

  // prog notes and prog details mirror value change

  function pNoteValueChange2() {
    document.getElementById('programmingdetails').value = document.getElementById('prog-notes-2').value;
  }

  function changeHardwareValue() {
    document.getElementById('pcmhardwaretype').value = document.getElementById('hardware').value;
  }
</script>

@endpush