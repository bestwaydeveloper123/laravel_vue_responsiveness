<template>
  <transition name="modal" id="myModal">
    <div @click="hideDropdowns" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-0">
            <div class="card-header py-1 pl-2" :class="headerStyle">
              <i class="fa fa-car"></i>
              {{ HeaderLabel }}
            </div>
            <div class="modal-body p-0 my-0">
              <table
                class="table table-sm table-bordered table-striped table-responsive-sm pull-left mb-0"
                width="2/3"
              >
                <tr>
                  <td class="py-0" width="15%">
                    <label class="col-form-label col-form-label-sm">Purchase From</label>
                  </td>
                  <td class="py-0" width="35%">
                    <input
                      class="form-control form-control-sm"
                      type="text"
                      v-model="vendorData.purchasedfrom"
                    />
                  </td>
                  <td class="py-0" width="15%">
                    <label class="col-form-label col-form-label-sm">Vendor Part Price</label>
                  </td>
                  <td class="py-0" width="35%">
                    <input
                      class="form-control form-control-sm"
                      type="text"
                      v-model="vendorData.partprice"
                    />
                  </td>
                </tr>
                <tr>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">
                      Sale
                      <i class="fa fa-user"></i> Stock#
                    </label>
                  </td>
                  <td class="py-0">
                    <textarea
                      class="form-control form-control-sm float-left"
                      type="text"
                      v-model="vendorData.salespersonstockno"
                      rows="1"
                      style="width:60%"
                    ></textarea>
                    <label
                      class="ml-2 switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
                    >
                      <input
                        class="switch-input"
                        type="checkbox"
                        id="instock"
                        v-model="vendorData.in_stock"
                      />
                      <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                    </label>
                    <span for="instock">In Stock</span>
                    <!-- <div class="custom-control custom-checkbox float-left ml-4">
                      <input
                        type="checkbox"
                        id="instock"
                        class="custom-control-input"
                        v-model="vendorData.in_stock"
                      />
                      <label
                        for="instock"
                        class="custom-control-label fsize"
                      ></label>
                    </div>-->
                  </td>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">Shipping Price</label>
                  </td>
                  <td class="py-0">
                    <input
                      class="form-control form-control-sm"
                      type="text"
                      v-model="vendorData.shippingprice"
                    />
                  </td>
                </tr>
                <tr>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">VIN</label>
                  </td>
                  <td class="py-0">
                    <input
                      class="form-control form-control-sm"
                      type="text"
                      v-model="vendorData.vin"
                    />
                  </td>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">Pickup Charge</label>
                  </td>
                  <td class="py-0">
                    <input
                      class="form-control form-control-sm"
                      type="text"
                      v-model="vendorData.pickupcharge"
                    />
                  </td>
                </tr>
                <tr>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">Date Purchased</label>
                  </td>
                  <td class="py-0">
                    <span style="float:left;width:87%;">
                      <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="vendorData.datepurchased"
                      />
                    </span>
                    <span
                      @click="todayDate('datepurchased')"
                      class="btn btn-sm badge-success ml-2 py-0"
                    >
                      <i class="fa fa-calendar-check-o"></i>
                    </span>
                  </td>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">Tracking #</label>
                  </td>
                  <td class="py-0">
                    <input
                      class="form-control form-control-sm"
                      type="text"
                      v-model="vendorData.trackingnumber"
                    />
                  </td>
                </tr>
                <tr>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">Delivery Date</label>
                  </td>
                  <td class="py-0">
                    <span style="float:left;width:87%;">
                      <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="vendorData.deliverydate"
                      />
                    </span>
                    <span
                      @click="todayDate('deliverydate')"
                      class="btn btn-sm badge-success ml-2 py-0"
                    >
                      <i class="fa fa-calendar-check-o"></i>
                    </span>
                  </td>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">Credit Card</label>
                  </td>
                  <td class="py-0">
                    <input
                      class="form-control form-control-sm"
                      type="text"
                      v-model="vendorData.creditcard"
                    />
                  </td>
                </tr>
                <tr>
                  <td colspan="4" class="card-header py-1" :class="headerStyle">
                    <i class="fa fa-retweet"></i> Refund Information
                  </td>
                </tr>
                <tr>
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">Who did you Speak to?</label>
                  </td>
                  <td class="py-0">
                    <select v-model="tracking.spoke_to" class="form-control form-control-sm">
                      <option value>Select</option>
                      <option
                        v-for="(user, index) in managers"
                        :key="index"
                        :value="user.id"
                      >{{ user.username }}</option>
                    </select>
                  </td>

                  <td class="py-0" colspan="2">
                    <div class="custom-control custom-checkbox float-left">
                      <input
                        type="checkbox"
                        v-model="tracking.refunded"
                        id="refunded"
                        class="custom-control-input"
                      />
                      <label for="refunded" class="custom-control-label fsize">Refunded</label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="4">
                    <div class="custom-control custom-checkbox">
                      <input
                        type="checkbox"
                        v-model="tracking.confirmation"
                        id="confirmation"
                        class="custom-control-input"
                      />
                      <label for="confirmation" class="custom-control-label fsize">Confirmation</label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="4" class="card-header py" :class="headerStyle">
                    <i class="fa fa-address-card"></i> Junkyard Address
                  </td>
                </tr>
                <tr>
                  <td class="py-0">
                    <label
                      for="shop-name-junkyard"
                      class="col-form-label col-form-label-sm mr-1"
                    >Shop Name</label>
                    <span style="font-size: 18px;" class="text-red">*</span>
                  </td>
                  <td class="py-0">
                    <input
                      id="shop-name-junkyard"
                      type="text"
                      class="form-control form-control-sm"
                      v-model="junkyardAddress.shopname"
                      @keyup="autoComplete"
                      autocomplete="off"
                    />
                    <div
                      v-if="shopShowList"
                      class="list-group shadow vbt-autcomplete-list"
                      style="position: absolute;width: 306.406px;overflow: auto;max-height: 200px;z-index: 1;"
                    >
                      <a
                        @click="selectValue(result)"
                        tabindex="0"
                        href="#"
                        class="vbst-item list-group-item list-group-item-action bg-light text-secondary"
                        v-for="result in results"
                        :key="result.id"
                      >
                        <span>
                          <strong>{{ result.shopname }}</strong>
                        </span>
                      </a>
                    </div>
                  </td>
                  <td class="py-0">
                    <label for="city-junkyard" class="col-form-label col-form-label-sm">City</label>
                  </td>
                  <td class="py-0">
                    <input
                      id="city-junkyard"
                      type="text"
                      class="form-control form-control-sm"
                      v-model="junkyardAddress.city"
                    />
                  </td>
                </tr>
                <tr>
                  <td class="py-0">
                    <label for="street-1-junkyard" class="col-form-label col-form-label-sm">Street1</label>
                  </td>
                  <td class="py-0">
                    <bootstrap-typeahead
                      id="street-1-junkyard"
                      v-model="addressSearch"
                      size="sm"
                      placeholder="Enter address..."
                      :pre-value="junkyardAddress.street1"
                      :data="address"
                      textVariant="secondary"
                      backgroundVariant="light"
                      @hit="selectAddress"
                      :serializer="listItem"
                      :real-value="listValue"
                    ></bootstrap-typeahead>
                  </td>
                  <td class="py-0">
                    <label for="state-junkyard" class="col-form-label col-form-label-sm">State</label>
                  </td>
                  <td class="py-0">
                    <select
                      id="state-junkyard"
                      class="form-control form-control-sm"
                      v-model="junkyardAddress.state"
                    >
                      <option
                        v-for="(state, index) in states.us"
                        :key="`us-${index}`"
                        :value="state.abbreviation"
                      >{{ state.name + ' ( ' + state.abbreviation + ', U.S. )' }}</option>
                      <option
                        v-for="(state, index) in states.ca"
                        :key="`ca-${index}`"
                        :value="state.abbreviation"
                      >{{ state.name + ' ( ' + state.abbreviation + ', Canada )' }}</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td class="py-0">
                    <label for="street-2-junkyard" class="col-form-label col-form-label-sm">Street2</label>
                  </td>
                  <td class="py-0">
                    <input
                      id="street-2-junkyard"
                      type="text"
                      class="form-control form-control-sm"
                      v-model="junkyardAddress.street2"
                    />
                  </td>
                  <td class="py-0">
                    <label for="zip-junkyard" class="col-form-label col-form-label-sm">Zip Code</label>
                  </td>
                  <td class="py-0">
                    <input
                      id="zip-junkyard"
                      class="form-control form-control-sm"
                      v-model="junkyardAddress.zipcode"
                    />
                  </td>
                </tr>
              </table>
            </div>
            <div class="modal-footer bg-gray-300 theme-color py-0 mt-1">
              <div
                class="alert alert-sm alert-danger alert-dismissible fade show py-1 mb-0"
                role="alert"
              >
                <strong>*</strong> needs to be filled for
                <strong>label generation</strong>.
              </div>
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
              <button class="btn btn-success" type="button" @click="saveChange">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import api from '../../api';
import BootstrapTypeahead from '../AddressEditCard/BootstrapTypeahead';

export default {
  components: {
    BootstrapTypeahead,
  },
  props: {
    data: {
      index: {
        type: Number,
        default: -1,
      },
      data: {
        type: Object,
        default: {},
      },
    },
    creditCard: {
      type: String,
      default: '',
    },
    allManagers: {
      type: String,
      default: '',
    },
    headerStyle: String,
    shopName: String,
  },
  data() {
    return {
      vendorData: {
        id: -1,
        purchasedfrom: '',
        salespersonstockno: '',
        vin: '',
        pricepaid: '',
        partprice: '',
        shippingprice: '',
        pickupcharge: '',
        datepurchased: '',
        deliverydate: '',
        trackingnumber: '',
        creditcard: '',
        in_stock: false,
      },
      tracking: {
        refunded: 0,
        spoke_to: '',
        confirmation: 0,
        label_creation_date: null,
      },
      junkyardAddress: {
        shopname: '',
        city: '',
        state: '',
        street1: '',
        street2: '',
        zipcode: '',
      },
      vendorIndex: -1,

      managers: null,
      states: {
        us: Object,
        ca: Object,
      },

      addressSearch: null,
      address: [],
      addressService: null,
      geocoder: null,
      results: [],
      queryShopName: this.shopName,
      shopShowList: false,
    };
  },
  created() {
    const { index, data } = this.data;
    if (index !== -1) {
      this.vendorIndex = index;
      Object.assign(this.vendorData, data);
      Object.assign(this.tracking, data.tracking);
      Object.assign(this.junkyardAddress, data.junkyardAddress);
    }
    if (this.creditCard) {
      this.vendorData.creditcard = this.creditCard;
    }
    if (this.allManagers) {
      this.managers = JSON.parse(this.allManagers);
    }
  },
  mounted() {
    api.fetchState().then(res => {
      this.states = JSON.parse(res.data);
    });
    // Get google map api.
    this.addressService = new google.maps.places.AutocompleteService();
    this.geocoder = new google.maps.Geocoder();
  },
  computed: {
    HeaderLabel() {
      const { index } = this.data;
      if (index != -1) {
        return `Vendor ${index + 1}`;
      }
      return 'Add a new Vendor';
    },
  },
  methods: {
    autoComplete() {
      this.results = [];
      if (this.junkyardAddress.shopname.length > 3) {
        this.results = [];
        axios
          .get('/api/getShopNameVendor', {
            params: { queryString: this.junkyardAddress.shopname },
          })
          .then(response => {
            this.shopShowList = true;
            this.results = response.data;
          });
      }
    },
    selectValue(value) {
      this.junkyardAddress.shopname = value.shopname;
      this.shopShowList = false;
      axios.post('/api/getMetadataVendor', { id: value.id }).then(response => {
        this.junkyardAddress.shopname = response.data.shopname;
        this.junkyardAddress.city = response.data.city;
        this.junkyardAddress.state = response.data.state;
        this.junkyardAddress.street1 = response.data.street1;
        this.junkyardAddress.street2 = response.data.street2;
        this.junkyardAddress.zipcode = response.data.zipcode;
      });
    },
    addInStock() {
      this.vendorData.salespersonstockno = 'In Stock';
    },
    todayDate(key) {
      const today = new Date();
      let dd = today.getDate();
      let mm = today.getMonth() + 1; //January is 0!
      const yyyy = today.getFullYear();

      dd = dd < 10 ? `0${dd}` : dd;
      mm = mm < 10 ? `0${mm}` : mm;

      this.vendorData[key] = `${yyyy}-${mm}-${dd}`;
    },
    close() {
      this.$emit('close');
    },
    saveChange() {
      if (this.hasChange(this.vendorData)) {
        this.vendorData.tracking = this.tracking;
        this.vendorData.junkyardAddress = this.junkyardAddress;
        this.$emit('save', this.vendorData);
      } else {
        this.$emit('close');
      }
    },
    hasChange(data) {
      return true;
    },
    selectAddress(data) {
      this.geocoder.geocode(
        { address: data.description },
        (results, status) => {
          if (status == 'OK') {
            const result = results[0];
            if (result.address_components.length > 0) {
              result.address_components.forEach(item => {
                item.types.forEach(type => {
                  if (type == 'postal_code') {
                    this.junkyardAddress.zipcode = item.long_name;
                  }
                });
              });
            }
          } else {
            console.log(status);
          }
        },
      );
      const addr = data.description.split(',');
      this.junkyardAddress.street1 = addr[0].trim();
      this.junkyardAddress.city = addr[1].trim();
      this.junkyardAddress.state = addr[2].trim();
    },
    listItem(item) {
      return item.description;
    },
    listValue(item) {
      return item.structured_formatting.main_text;
    },
    addressSuggestions(predictions, status) {
      if (status != google.maps.places.PlacesServiceStatus.OK) {
        console.log(status);
        return;
      }
      this.address = [];
      predictions.forEach(item => {
        this.address.push(item);
      });
    },
    hideDropdowns() {
      this.shopShowList = false;
    },
  },
  watch: {
    addressSearch(input) {
      if (input.length < 5) {
        return;
      }

      this.addressService.getPlacePredictions(
        {
          input,
          types: ['address'],
          componentRestrictions: { country: ['us', 'ca'] },
        },
        this.addressSuggestions,
      );
      this.junkyardAddress.street1 = input;
    },
  },
};
</script>

<style scoped>
.fsize {
  font-size: 0.875rem;
}
.ml-150 {
  margin-left: 150px;
}
.pt-10 {
  padding-top: 10px !important;
}
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}
.modal-wrapper {
  display: table-cell;
  vertical-align: top;
}
.modal-container {
  width: 70%;
  margin: 30px auto;
  padding: 0px 0px 3px 0px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}
.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}
.modal-body {
  margin: 20px 0;
}
.modal-default-button {
  float: right;
}
.modal-enter {
  opacity: 0;
}
.modal-leave-active {
  opacity: 0;
}
.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
