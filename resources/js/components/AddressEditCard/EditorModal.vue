<template>
  <transition name="modal">
    <div @click="hideDropdowns" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header py-1" :class="headerStyle">
              <i class="fa fa-car"></i>Customer Information
            </div>
            <div class="modal-body p-0 my-1">
              <table
                class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
                width="2/3"
              >
                <tbody>
                  <tr>
                    <td class="py-0" width="15%">
                      <label class="col-form-label col-form-label-sm mr-1" for="shop-name">Shop Name</label>
                      <span style="font-size: 18px;" class="text-red">*</span>
                    </td>
                    <td class="py-0" width="35%">
                      <input
                        class="form-control form-control-sm"
                        id="shop-name"
                        type="text"
                        v-model="queryShopName"
                        v-on:keyup="autoComplete('shopname')"
                        autocomplete="off"
                      />
                      <div
                        v-if="shopShowList"
                        class="list-group shadow vbt-autcomplete-list"
                        style="position: absolute;width: 306.406px;max-height: 290px;overflow: auto;"
                      >
                        <a
                          @click="selectValue('shopname', result)"
                          tabindex="0"
                          href="#"
                          class="vbst-item list-group-item list-group-item-action bg-light text-secondary"
                          v-for="result in results"
                          :key="result.id"
                          v-if="result.shopname != ''"
                        >
                          <span>
                            <strong>{{ result.shopname }}</strong>
                          </span>
                        </a>
                      </div>
                    </td>
                    <td class="py-0" width="10%">
                      <label class="col-form-label col-form-label-sm" for="text-phone">Phone</label>
                    </td>
                    <td class="py-0" width="40%">
                      <input
                        id="text-phone"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.phone"
                        @blur="phoneblurred"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="py-0">
                      <label
                        class="col-form-label col-form-label-sm mr-1"
                        for="text-shipto"
                      >Customer Name</label>
                      <span style="font-size: 18px;" class="text-red">*</span>
                    </td>
                    <td class="py-0">
                      <input
                        id="text-shipto"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="queryShipTo"
                        v-on:keyup="autoComplete('custname')"
                        autocomplete="off"
                      />
                      <div
                        v-if="custShowList"
                        class="list-group shadow vbt-autcomplete-list"
                        style="position: absolute;width: 306.406px;max-height: 290px;overflow: auto;"
                      >
                        <a
                          @click="selectValue('custname',result)"
                          tabindex="0"
                          href="#"
                          class="vbst-item list-group-item list-group-item-action bg-light text-secondary"
                          v-for="result in results"
                          :key="result.id"
                          v-if="result.shipto != ''"
                        >
                          <span>
                            <strong>{{ result.shipto }}</strong>
                          </span>
                        </a>
                      </div>
                    </td>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-phone2">Phone 2</label>
                    </td>
                    <td class="py-0">
                      <input
                        id="text-phone2"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.phone2"
                        @blur="phone2blurred"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-street1">Street1</label>
                    </td>
                    <td class="py-0">
                      <bootstrap-typeahead
                        id="text-street1"
                        size="sm"
                        placeholder="Enter address..."
                        textVariant="secondary"
                        backgroundVariant="light"
                        v-model="addressSearch"
                        :pre-value="customer.street1"
                        :data="address"
                        :serializer="listItem"
                        :real-value="listValue"
                        @hit="selectAddress"
                      ></bootstrap-typeahead>
                    </td>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-email">E-mail</label>
                    </td>
                    <td class="py-0">
                      <input
                        id="text-email"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.email"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-street2">Street2</label>
                    </td>
                    <td class="py-0">
                      <input
                        id="text-street2"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.street2"
                      />
                    </td>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-email2">E-mail 2</label>
                    </td>
                    <td class="py-0">
                      <input
                        id="text-email2"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.email2"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-city">City</label>
                    </td>
                    <td class="py-0">
                      <input
                        id="text-city"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.city"
                      />
                    </td>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-level">Account Type</label>
                    </td>
                    <td class="py-0">
                      <select
                        id="text-level"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.level"
                      >
                        <option value="Normal">Normal</option>
                        <option value="Plus (2+ sales)">Plus (2+ sales)</option>
                        <option value="Premier (3+ sales)">Premier (3+ sales)</option>
                        <option value="Platinum (5+ sales)">Platinum (5+ sales)</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="select-state">State</label>
                    </td>
                    <td class="py-0">
                      <select
                        id="select-state"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.state"
                        @change="customState(customer.state)"
                      >
                        <option selected value></option>
                        <option
                          v-if="customStateName"
                          selected
                          :value="customStateName"
                        >{{ customStateName }}</option>
                        <option value="addState">-- Add Custom State --</option>
                        <option
                          v-for="(state, index) in states.us"
                          :key="`${index}-us`"
                          :value="state.abbreviation"
                        >{{ state.name + ' ( ' + state.abbreviation + ', U.S. )' }}</option>
                        <option
                          v-for="(state, index) in states.ca"
                          :key="`${index}-ca`"
                          :value="state.abbreviation"
                        >{{ state.name + ' ( ' + state.abbreviation + ', Canada )' }}</option>
                      </select>
                      <input
                        v-if="showStateInput"
                        id="select-state"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customStateName"
                      />
                    </td>
                    <td class="py-0"></td>
                    <td class="py-0"></td>
                  </tr>
                  <tr>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-zip">ZIP</label>
                    </td>
                    <td class="py-0">
                      <input
                        id="text-zip"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.zip"
                      />
                    </td>
                    <td class="py-0"></td>
                    <td class="py-0"></td>
                  </tr>
                  <tr>
                    <td class="py-0">
                      <label class="col-form-label col-form-label-sm" for="text-country">Country</label>
                    </td>
                    <td class="py-0">
                      <input
                        id="text-country"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="customer.country"
                      />
                    </td>
                    <td class="py-0"></td>
                    <td class="py-0"></td>
                  </tr>
                </tbody>
              </table>
              <div class="put-right">
                <label class="col-form-label col-form-label-sm pb-0 mr-2">
                  <span class="text-red">*</span> One has to be filled, Shop name or Customer Name for Labels.
                </label>
              </div>
            </div>
            <div class="modal-footer py-0 mt-1 bg-gray-300 theme-color">
              <div
                v-if="!validName"
                class="alert alert-sm alert-danger alert-dismissible fade show py-1 mb-0"
                role="alert"
              >
                {{ validMsg }} field is
                <strong>required</strong>.
                <!-- <button
                  type="button"
                  class="close p-1 mr-2"
                  data-dismiss="alert"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>-->
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
import BootstrapTypeahead from './BootstrapTypeahead';
import api from '../../api';

export default {
  components: {
    BootstrapTypeahead,
  },
  props: {
    data: Object,
    headerStyle: String,
    shopName: String,
    shipTo: String,
  },
  data() {
    return {
      customer: {
        shopname: '',
        shipto: '',
        street1: '',
        street2: '',
        city: '',
        state: '',
        zip: '',
        country: '',
        phone: '',
        phone2: '',
        email: '',
        email2: '',
        level: '',
      },
      states: [],

      addressSearch: '',
      address: [],
      addressService: null,
      geocoder: null,
      validName: true,
      validMsg: '',
      queryShopName: this.shopName,
      queryShipTo: this.shipTo,
      results: [],
      shopShowList: false,
      custShowList: false,
      showStateInput: false,
      customStateName: '',
    };
  },
  created() {
    Object.assign(this.customer, this.data);
    api.fetchState().then(res => {
      this.states = JSON.parse(res.data);
    });
  },
  mounted() {
    this.customStateName = this.customer ? this.customer.state : '';

    this.addressService = new google.maps.places.AutocompleteService();
    this.geocoder = new google.maps.Geocoder();
  },
  methods: {
    customState(state) {
      this.showStateInput = false;
      if (state == 'addState') {
        if(this.customStateName != 'addState'){
          this.customer.state = this.customStateName;
        } else {
          this.customer.state = '';
        }
        this.showStateInput = true;
      }
    },
    autoComplete(str) {
      this.results = [];
      if (str == 'shopname') {
        if (this.queryShopName.length > 3) {
          this.results = [];
          axios
            .get('/api/getshopname', {
              params: { queryString: this.queryShopName },
            })
            .then(response => {
              this.custShowList = false;
              this.shopShowList = true;
              this.results = response.data;
            });
        }
      } else if (str == 'custname') {
        if (this.queryShipTo.length > 3) {
          this.results = [];
          axios
            .get('/api/getcustomername', {
              params: { queryString: this.queryShipTo },
            })
            .then(response => {
              this.shopShowList = false;
              this.custShowList = true;
              this.results = response.data;
            });
        }
      }
    },
    selectValue(str, value) {
      if (str == 'shopname') {
        this.queryShopName = value.shopname;
        this.shopShowList = false;
        axios.post('/api/getMetadata', { id: value.id }).then(response => {
          this.queryShopName = response.data.shopname;
          this.queryShipTo = response.data.shipto;
          this.customer.street1 = response.data.street1;
          this.customer.street2 = response.data.street2;
          this.customer.city = response.data.city;
          this.customer.state = response.data.state;
          this.customer.zip = response.data.zip;
          this.customer.country = response.data.country;
          this.customer.phone = response.data.phone;
          this.customer.phone2 = response.data.phone2;
          this.customer.email = response.data.email;
          this.customer.email2 = response.data.email2;
          this.customer.level = response.data.level;
        });
      } else if (str == 'custname') {
        this.queryShipTo = value.shipto;
        this.custShowList = false;
        axios.post('/api/getMetadata', { id: value.id }).then(response => {
          this.queryShopName = response.data.shopname;
          this.queryShipTo = response.data.shipto;
          this.customer.street1 = response.data.street1;
          this.customer.street2 = response.data.street2;
          this.customer.city = response.data.city;
          this.customer.state = response.data.state;
          this.customer.zip = response.data.zip;
          this.customer.country = response.data.country;
          this.customer.phone = response.data.phone;
          this.customer.phone2 = response.data.phone2;
          this.customer.email = response.data.email;
          this.customer.email2 = response.data.email2;
          this.customer.level = response.data.level;
        });
      }
    },
    phoneblurred(e) {
      this.customer.phone = this.addNumberMeta(e);
    },
    phone2blurred(e) {
      this.customer.phone2 = this.addNumberMeta(e);
    },
    addNumberMeta(e) {
      if (e.target.value.length == 10) {
        let str = e.target.value;
        return (
          '(' + str.slice(0, 3) + ') ' + str.slice(3, 6) + ' - ' + str.slice(6)
        );
      }
      return e.target.value;
    },
    close() {
      this.$emit('close');
    },
    saveChange() {
      this.customer.shopname = this.queryShopName;
      this.customer.shipto = this.queryShipTo;
      if (!this.customer.level) {
        this.customer.level = 'Normal';
      }
      if (this.customer.shopname || this.customer.shipto) {
        this.$emit('save', this.customer);
      } else {
        this.validName = false;
      }
      if (
        this.customer.shopname == null ||
        (this.customer.shopname == null && this.customer.shipto == null)
      ) {
        this.validMsg = 'Shop Name';
      } else if (this.customer.shipto == null) {
        this.validMsg = 'Customer Name';
      } else {
        this.validMsg = '*';
      }
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
                    this.customer.zip = item.long_name;
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
      this.customer.street1 = addr[0].trim();
      this.customer.city = addr[1].trim();
      this.customer.state = addr[2].trim();
      this.customer.country = addr[3].trim();
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
      this.custShowList = false;
    },
  },
  watch: {
    customStateName: function(val, oldVal) {
      val
        ? (this.customer.state = val)
        : (this.customer.state = this.customer.state);
    },
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
      this.customer.street1 = input;
    },
  },
};
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.5s ease;
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


