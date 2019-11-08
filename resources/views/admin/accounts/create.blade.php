@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
	<div class="main" id="vue-app">
		<form method="POST" action="/accounts/">
			@include('admin.accounts.misc.breadcrumbs', ['saveId' => 'account-save'])
      @csrf
      <customer-information
        user-role="{{ auth()->user()->role }}"
        account-users="{{ $accountusers }}"
      ></customer-information>

      <order-information
        user-role="{{ auth()->user()->role }}"
        account-users="{{ $accountusers }}"
        role-name="{{ $rolename }}"
        user-name="{{ auth()->user()->username }}"
      ></order-information>

      <vendor-edit credit-card="{{ $creditcard }}"></vendor-edit>

      <programming-information
        user-role="{{ auth()->user()->role }}"
        role-name="{{ $rolename }}"
        user-name="{{ auth()->user()->username }}"
      ></programming-information>

			@include('admin.accounts.misc.accountnotes')

			<div class="card mb-0">
				<div class="card-header bg-gray-700 theme-color text-light py-1">
					<a class="text-light" data-toggle="collapse" href="#shippingAccordition" aria-expanded="true" aria-controls="shippingAccordition">
						<strong><i class="fa fa-edit"></i> Shipping<i class="fa fa-caret-down"></i></strong>
					</a>
				</div>
				<div class="card-body py-0 px-2" data-children=".item">
          <div class="item collapse {{ intval(auth()->user()->role) == 3 ? '' : 'show' }}" id="shippingAccordition" role="tabpanel">
            <div class="row px-1 pt-1">
              <div class="col-sm-12 col-md-4 px-2">
                <address-edit-card id="address-edit-card-1" />
              </div>
              <div class="col-sm-12 col-md-4 px-2">
                <div class="card mb-1">
                  <div class="card-header bg-gray-700 theme-color text-light py-1">
                    <strong><i class="fa fa-wrench"></i> Options</strong>
                  </div>
                  <div class="card-body pt-0 pb-1">
                    <table>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 py-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="onedayhandling-2" value="1">
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td class="py-0">1 Day Handling</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 py-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="sticker-2" value="1">
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td class="py-0">Sticker</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 py-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="fixplugorcase-2" value="1">
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td class="py-0">Fix Plug/Case</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 py-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="changelabel-2" value="1">
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td class="py-0">Change Label</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 py-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="removebracket-2" value="1">
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td class="py-0">Remove Bracket</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 py-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="wrongpart-2" value="1">
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td class="py-0">Wrong Part</td>
                      </tr>
                      <tr>
                        <td>
                          <label class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 py-1">
                            <input class="switch-input" type="hidden" value="0">
                            <input class="switch-input" type="checkbox" id="progmods-2" value="1">
                            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                          </label>
                        </td>
                        <td class="py-0">Prog Mods</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-sm-12 col-md-4 px-2">
                <div class="card mb-1">
                  <div class="card-header bg-gray-700 theme-color text-light py-1">
                    <strong><i class="fa fa-truck"></i> Misc</strong>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-sm table-responsive-sm mb-0 p-1" width="50%">
                      <tr>
                        <td class="py-0" width="25%"><label class="col-form-label col-form-label-sm" for="order-type2">Order Type</label></td>
                        <td class="py-0"><input id="order-type2" class="form-control form-control-sm" type="text" readonly></td>
                      </tr>
                      <tr>
                        <td class="py-0"><label class="col-form-label col-form-label-sm" for="shipping-instructions">Shipping Instructions</label></td>
                        <td class="py-0"><textarea id="shipping-instructions" class="form-control form-control-sm" name="shippinginstructions" rows="1"></textarea></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
				</div>
			</div>

      @include('admin.accounts.misc.breadcrumbs')

		</form>
	</div>
</div>
@stop

@push('account_create')
<script>
	$('body').on('keydown', 'input,select', function(e) {
		var self = $(this),
			form = self.parents('form:eq(0)'),
			focusable, next;
		if (e.keyCode == 13) {
			$(this).closest('tr').next().find('input,a,select,button,textarea').focus();
			// e.preventDefault();
			return false;
		}
	});

	// sync changes
	$(document).ready(function() {
		$('#customer-level-1').change(function() {
			$('#address-edit-card')[0].__vue__.data.level = this.value;
			$('#address-edit-card-1')[0].__vue__.data.level = this.value;
		})

		$('#order-type-ctrl1').change(function() {
			$('#order-type1').val(this.value);
			$('#order-type2').val(this.value);
		})
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

	//Current Date
	// document.getElementById("date-purchased-ctrl").valueAsDate = new Date();
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);

	var today = now.getFullYear() + "-" + (month) + "-" + (day);

	$('#date-purchased-ctrl').val(today);

	$("#deltodayDate").click(function() {
		$('#requireddeliverydate').val(today);
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

	function orderTypeValueChange1() {
		document.getElementById('order-type-ctrl2').value = document.getElementById('order-type-ctrl1').value;
	}

	function orderTypeValueChange2() {
		document.getElementById('order-type-ctrl1').value = document.getElementById('order-type-ctrl2').value;
	}

	// prog notes and prog details mirror value change

	function pNoteValueChange() {
		document.getElementById('programmingdetails').value = document.getElementById('prog-notes').value;
	}

	function changeHardwareValue2() {
		document.getElementById('pcmhardwaretype').value = document.getElementById('hardware2').value;
  }

</script>
@endpush
