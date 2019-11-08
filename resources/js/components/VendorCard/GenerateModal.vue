<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header py-1" :class="headerStyle">
              <i class="fa fa-user"></i> Generate Label
              <i class="fa fa-plus"></i>
            </div>
            <div class="modal-body p-0 my-1">
              <table
                class="table table-sm table-bordered table-striped table-responsive-sm pull-left mb-0"
                width="2/3"
              >
                <tr>
                  <td class="py-0" width="10%">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="package-type"
                    >Package</label>
                  </td>
                  <td class="py-1" width="40%">
                    <select
                      v-model="packagetype"
                      id="package-type"
                      class="form-control form-control-sm"
                      @change="selectPackage"
                    >
                      <option disabled value selected>Select</option>
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
                <tr v-if="resData">
                  <td class="py-0" width="10%">
                    <label class="col-form-label col-form-label-sm">Rate/Carrier</label>
                  </td>
                  <td>
                    <select
                      v-model="rate"
                      v-html="resData.data.options"
                      class="form-control form-control-sm"
                    ></select>
                  </td>
                </tr>
              </table>
            </div>
            <div class="modal-footer py-0 mt-1 bg-gray-300 theme-color">
              <button v-if="resData" class="btn btn-primary" type="button" @click="buy">Buy</button>
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
            </div>
          </div>
        </div>
      </div>
      <loading :active.sync="isLoading" :is-full-page="true"></loading>
    </div>
  </transition>
</template>

<script>
import Loading from 'vue-loading-overlay';
import api from '../../api';

import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  components: {
    Loading,
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
    accountId: String,
    pricePaid: String,
    customerData: Object,
    headerStyle: String,
  },
  data() {
    return {
      junkyardAddress: {
        shopname: '',
        city: '',
        state: '',
        street1: '',
        street2: '',
        zipcode: '',
      },
      trackingId: null,
      packagetype: '',
      rate: '',
      resData: null,

      isLoading: false,
    };
  },
  created() {
    const { index, data } = this.data;
    if (index != -1) {
      Object.assign(this.junkyardAddress, data.junkyardAddress);
      this.trackingId = data.id;
    }
  },
  computed: {
    getPackageTypeToPost() {
      if (this.packagetype == 'REQUIRED'
        || this.packagetype == 'By Weight (3lb) - UPS')
      {
        return "";
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
        .CreateJunkyardToUs({
          fsid: this.accountId,
          packagetype: this.getPackageTypeToPost,
          pricepaid: this.pricePaid,
          suppliershippingname: this.junkyardAddress.shopname,
          suppliershippingstreet: this.junkyardAddress.street1,
          suppliershippingcity: this.junkyardAddress.city,
          suppliershippingstate: this.junkyardAddress.state,
          suppliershippingzip: this.junkyardAddress.zipcode,
        })
        .then(res => {
          if (res) {
            setTimeout(() => {
              this.resData = res.data;
              this.isLoading = false;
            }, 500);
          }
        })
        .catch(err => {
          (this.isLoading = false), alert(err);
        });
    },
    buy() {
      this.isLoading = true;
      api
        .BuyEasyPostJunkyard({
          fsid: this.accountId,
          packagetype: this.getPackageTypeToPost,
          pricepaid: this.pricePaid,
          shiptoname: this.junkyardAddress.shopname,
          customerstreet1: this.junkyardAddress.street1,
          customerstreet2: this.junkyardAddress.street2,
          customercity: this.junkyardAddress.city,
          customerstateprovince: this.junkyardAddress.state,
          customerzippostalcode: this.junkyardAddress.zipcode,
          rate: this.rate,
          trackingid: this.trackingId
        })
        .then(res => {
          if (res) {
            setTimeout(() => {
              this.isLoading = false;
            }, 500);
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
          }
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
