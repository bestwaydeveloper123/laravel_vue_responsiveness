<div class="card mb-1">
    <div class="card-header card-header pt-1 pb-1 bg-gray-700 theme-color">
      <a class="text-light float-left" data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion6" aria-expanded="true" aria-controls="exampleAccordion6">
        <strong><i class="fa fa-edit"></i> Shipping<i class="fa fa-caret-down"></i></strong>
      </a>
    </div>
    <div class="card-body pt-0 pb-0">
      <div id="exampleAccordion" data-children=".item">
        <div class="item">
          <div class="collapse {{ intval(auth()->user()->role) == 3 ? '' : 'show' }} table-responsive" id="exampleAccordion6" role="tabpanel">
            <div class="row">
              <div class="col-sm-6 col-md-4 pull-left mt-1">
                <address-edit-card
                  :customer-data="{{ json_encode($customermetadata) }}"
                  account-id="{{ $account->id }}"
                  id="address-edit-card-1"
                />
              </div>

              <div class="col-sm-6 col-md-4 pull-left">
                <div class="card mb-1 mt-1">
                  <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
                    <strong><i class="fa fa-wrench"></i> Options</strong>
                  </div>
                  <div class="card-body pt-0 pb-1">
                    <table>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="onedayhandling-2" value="1" {{ ( $account['onedayhandling']=='1' ? 'checked' : '') }}>
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td class="pb-0 pt-0">1 Day Handling</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="sticker-2" value="1" {{ ( $account['sticker']=='1' ? 'checked' : '') }}>
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td>Sticker</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="fixplugorcase-2" value="1" {{ ( $account['fixplugorcase']=='1' ? 'checked' : '') }}>
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td>Fix Plug/Case</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="changelabel-2" value="1" {{ ( $account['changelabel']=='1' ? 'checked' : '') }}>
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td>Change Label</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="removebracket-2" value="1" {{ ( $account['removebracket']=='1' ? 'checked' : '') }}>
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td>Remove Bracket</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="wrongpart-2" value="1" {{ ( $account['wrongpart']=='1' ? 'checked' : '') }}>
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td>Wrong Part</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="progmods-2" value="1" {{ ( $account['prog_mods']=='1' ? 'checked' : '') }}>
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td>Prog Mods</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-md-4 pull-left">
                <div class="card mt-1 mb-1">
                  <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
                    <strong><i class="fa fa-truck"></i> Misc</strong>
                  </div>
                  <div class="card-body pt-0 pb-0">
                    <table class="table table-sm table-responsive-sm table-bordered mb-0" width="50%">
                      <tr>
                        <td class="pt-0 pb-0" width="25%">
                          <label class="col-form-label col-form-label-sm" for="order-type2">Order Type</label>
                        </td>
                        <td class="pt-0 pb-0">
                          <input class="form-control form-control-sm" id="order-type2" type="text" value="{{ $account->ordertype }}" readonly>
                        </td>
                      </tr>
                      <tr>
                        <td class="pt-0 pb-0">
                          <label class="col-form-label col-form-label-sm" for="shippinginstructions">Shipping Instructions</label>
                        </td>
                        <td class="pt-0 pb-0">
                          <textarea class="form-control form-control-sm" id="shippinginstructions" name="shippinginstructions" value="{{ $account->shippinginstructions }}" rows="1">{{ $account->shippinginstructions }}</textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="pt-0 pb-0">
                          <label class="col-form-label col-form-label-sm" for="shippinginstructions">Order Status</label>
                        </td>
                        <td class="pt-0 pb-0">
                          <part-location
                            part-location = true
                            account-team="{{ $account->accountteam }}"
                            role-name="{{ $rolename }}"
                            :account-id="{{ $account->id }}"
                          />
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="card mb-1">
              <div class="card-header py-1 bg-gray-700 theme-color text-light">
                <strong><i class="fa fa-info" aria-hidden="true"></i> Information</strong>
              </div>
              <div class="card-body p-0">
                <table class="table table-sm p-0 mb-0">
                  <tr>
                    <td class="py-0" width="15%"><label class="col-form-label col-form-label-sm" for="item-purchased-shipping">Item Purchased</label></td>
                    <td class="py-0"><textarea id="item-purchased-shipping" class="form-control form-control-sm" type="text" rows="1" readonly>{{ $account->itempurchased }}</textarea></td>
                  </tr>
                </table>
              </div>
            </div>
            <customer-tracking
              :customer-data="{{ json_encode($customermetadata) }}"
              :tracking-data="{{ json_encode($trackings) }}"
              :account="{{ json_encode($account) }}"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
