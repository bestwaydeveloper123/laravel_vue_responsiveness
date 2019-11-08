<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header py-1" :class="headerStyle">
              Generate Label <i class="fa fa-plus"></i>
            </div>
            <div class="modal-body py-0 pl-0 pr-0 mb-1 mt-1">
              <table
                class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
                width="2/3"
              >
                <tr>
                  <td class="py-0" width="10%">
                    <label class="col-form-label col-form-label-sm">Direction</label>
                  </td>
                  <td class="py-1" width="40%">
                    <select
                      v-model="direction"
                      name="direction"
                      id="direction"
                      class="form-control form-control-sm"
                      disabled
                    >
                      <option disabled value selected>Select</option>
                      <option value="To Customer">OutBound</option>
                      <option value="To Fs1inc">InBound</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td class="py-0" width="10%">
                    <label class="col-form-label col-form-label-sm">Package</label>
                  </td>
                  <td class="py-1" width="40%">
                    <select
                      v-model="packagetype"
                      name="packagetype"
                      id="packagetype"
                      class="form-control form-control-sm"
                      @change="selectPackage"
                    >
                      <option disabled selected value>Select</option>
                      <option>REQUIRED</option>
                      <option>By Weight (3lb) - UPS</option>
                      <option value="FlatRateEnvelope">FlatRateEnvelope - USPS</option>
                      <option value="FlatRatePaddedEnvelope">FlatRatePaddedEnvelope - USPS</option>
                      <option value="MediumFlatRateBox">MediumFlatRateBox - USPS</option>
                      <option value="RegionalRateBoxA">RegionalRateBoxA - USPS</option>
                      <option value="RegionalRateBoxB">RegionalRateBoxB - USPS</option>
                    </select>
                  </td>
                </tr>
                <tr v-if="options">
                  <td class="py-0" width="10%">
                    <label class="col-form-label col-form-label-sm">Rate/Carrier</label>
                  </td>
                  <td>
                    <select v-model="rate" v-html="options" class="form-control form-control-sm">
                    </select>
                  </td>
                </tr>
              </table>
            </div>
            <div class="modal-footer bg-gray-300 theme-color py-0 mt-1">
              <button v-if="options" class="btn btn-primary" type="button" @click="buy">Buy</button>
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
            </div>
          </div>
        </div>
      </div>
      <loading :active.sync="isLoading" is-full-page></loading>
    </div>
  </transition>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import api from '../../api';

export default {
  components: {
    Loading,
  },
  props: {
    tracking: {
      index: {
        type: Number,
        default: -1,
      },
      date: {
        type: Object,
        default: {},
      },
    },
    accountId: Number,
    pricePaid: String,
    customerData: Object,
    headerStyle: String,
  },
  data() {
    return {
      packagetype: '',
      rate: '',
      options: '',
      shipment: '',

      isLoading: false,
    };
  },
  computed: {
    direction() {
      const { direction } = this.tracking.data;
      if (direction == 'OutBound') {
        return 'To Customer';
      } else if (direction == 'InBound') {
        return 'To Fs1inc';
      }
      return '';
    },
    packageTypeToPost() {
      if (this.packagetype == 'REQUIRED'
        || this.packagetype == 'By Weight (3lb) - UPS')
      {
        return null;
      }
      return this.packagetype;
    }
  },
  methods: {
    close() {
      this.$emit('close');
    },
    selectPackage() {
      this.isLoading = true;
      api
        .CreateEasyPost({
          fsid: this.accountId,
          packagetype: this.packageTypeToPost,
          pricepaid: this.pricePaid,
          shiptoname: this.customerData.shipto,
          customerstreet1: this.customerData.street1,
          customerstreet2: this.customerData.street2,
          customercity: this.customerData.city,
          customerstateprovince: this.customerData.state,
          customerzippostalcode: this.customerData.zip,
          direction: this.direction,
          tracking_id: this.tracking.data.id,
      })
        .then(res => {
          setTimeout(() => {
            this.options = res.data.data.options;
            this.shipment = res.data.data.shipment;
            this.isLoading = false;
          }, 500);
        })
        .catch(err => {
          (this.isLoading = false), alert(err);
        });
    },
    buy() {
      this.isLoading = true;
      api
        .BuyEasyPost({
          fsid: this.accountId,
          packagetype: this.packageTypeToPost,
          pricepaid: this.pricePaid,
          shiptoname: this.customerData.shipto,
          shopname: this.customerData.shopname,
          customerstreet1: this.customerData.street1,
          customerstreet2: this.customerData.street2,
          customercity: this.customerData.city,
          customerstateprovince: this.customerData.state,
          customerzippostalcode: this.customerData.zip,
          direction: this.direction,
          RateCarrier: this.rate,
          tracking_id: this.tracking.data.id,
      })
        .then(res => {
          setTimeout(() => {
            this.isLoading = false;
            if (res.data.success) {
              this.$toast.open({
                message: 'Success!',
                position: 'top-right',
              });
              this.$emit('update', res.data.data.tracking);
            } else {
              this.$toast.open({
                message: res.data.msg,
                position: 'top-right',
                type: 'error',
              });
              this.$emit('close');
            }
          }, 500);
        })
        .catch(err => {
          (this.isLoading = false), alert(err);
        });
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
  transition: opacity 0.3s ease;
}
.modal-wrapper {
  display: table-cell;
  vertical-align: top;
}
.modal-container {
  width: 50%;
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
