<template>
  <div class="card mb-0">
    <div class="card-header py-1 bg-gray-700 theme-color text-light">
      <strong>
        <i class="fa fa-edit"></i> Bulk Add Accounts
      </strong>
    </div>
    <div class="card-body">
      <div class="form-check">
        <input
          class="form-check-input"
          type="radio"
          id="option-fetch"
          value="auto-fetch"
          v-model="inputType"
          checked
        />
        <label class="form-check-label" for="option-fetch">
          Add
          <b>Magento</b> Order Information by fetching from
          <b>fs1inc.com</b>
        </label>
      </div>
      <template v-if="inputType == 'auto-fetch'">
        <div class="container-fluid py-2">
          <button class="btn btn-success" @click="fetchFromFs1inc">Fetch Bulk</button>
          <button class="btn btn-success" @click="submitBulkInfo">Submit</button>
          <button
            class="btn btn-success"
            @click="downloadCSV('fromMagento')"
            :disabled="!bulkInfo"
          >Download CSV</button>
        </div>
        <bulk-info-table v-if="bulkInfo" order-type="Website" :bulk-info="bulkInfo"></bulk-info-table>
      </template>

      <div class="form-check">
        <input
          class="form-check-input"
          type="radio"
          id="option-ebay-order"
          value="ebay-order"
          v-model="inputType"
        />
        <label class="form-check-label" for="option-ebay-order">
          Add
          <b>Ebay</b> Order Information by fetching from
          <b>Ebay.com</b>
        </label>
      </div>
      <template v-if="inputType == 'ebay-order'">
        <div class="container-fluid py-2">
          <button class="btn btn-success" @click="fetchOrderFromEbay">Fetch Orders</button>
          <button class="btn btn-success" @click="submitOrderFromEbay">Submit</button>
          <button
            class="btn btn-success"
            @click="downloadCSV('fromEbay')"
            :disabled="!bulkInfo"
          >Download CSV</button>
        </div>
        <bulk-info-table v-if="bulkInfo" order-type="eBay- fs1inc" :bulk-info="bulkInfo"></bulk-info-table>
      </template>

      <div class="form-check">
        <input
          class="form-check-input"
          type="radio"
          id="option-magento-order"
          value="magento-order"
          v-model="inputType"
        />
        <label class="form-check-label" for="option-magento-order">
          Input
          <b>Magento</b>(fs1inc.com) Order Information
        </label>
      </div>
      <template v-if="inputType == 'magento-order'">
        <div class="form-group pt-2">
          <textarea class="form-control" v-model="manualOrderInfo" rows="6"></textarea>
        </div>
        <div class="container-fluid py-2">
          <button class="btn btn-success" @click="checkManualOrder">Check Orders</button>
          <button class="btn btn-success" @click="submitBulkInfo">Submit</button>
          <button
            class="btn btn-success"
            @click="downloadCSV('fromCustomMagento')"
            :disabled="!bulkInfo"
          >Download CSV</button>
        </div>
        <bulk-info-table v-if="bulkInfo" order-type="Website" :bulk-info="bulkInfo"></bulk-info-table>
      </template>

      <div class="form-check">
        <input
          class="form-check-input"
          type="radio"
          id="option-ebay-order1"
          value="ebay-order1"
          v-model="inputType"
        />
        <label class="form-check-label" for="option-ebay-order1">
          Input
          <b>eBay</b> Order Information
        </label>
      </div>
      <template v-if="inputType == 'ebay-order1'">
        <div class="form-group pt-2">
          <textarea class="form-control" v-model="manualOrderInfo" rows="6"></textarea>
        </div>
        <div class="container-fluid py-2">
          <button class="btn btn-success" @click="checkManualOrder">Check Orders</button>
          <button class="btn btn-success" @click="submitBulkInfo">Submit</button>
          <button
            class="btn btn-success"
            @click="downloadCSV('fromCustomEbay')"
            :disabled="!bulkInfo"
          >Download CSV</button>
        </div>
        <bulk-info-table v-if="bulkInfo" order-type="eBay- fs1inc" :bulk-info="bulkInfo"></bulk-info-table>
      </template>
    </div>
    <loading :active.sync="isLoading" is-full-page />
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import BulkInfoTable from './BulkInfoTable';
import api from '../../api';

export default {
  name: 'BulkAddAccount',
  components: {
    BulkInfoTable,
    Loading,
  },
  data() {
    return {
      inputType: 'auto-fetch',
      manualOrderInfo: '',
      bulkInfo: null,
      isLoading: false,
      EbayOrderData: [],
      MagentoOrderData: [],
      customMagento: [],
      customEbay: []
    };
  },
  methods: {
    fetchFromFs1inc() {
      this.isLoading = true;
      api
        .fetchOrderInfo()
        .then(res => {
          if (res.data.success) {
            setTimeout(() => {
              this.isLoading = false;
              this.bulkInfo = res.data.data;
              this.MagentoOrderData = res.data.data;
              this.$toast.open({
                message: res.data.message,
                position: 'top',
              });
            }, 500);
          } else {
            this.isLoading = false;
            this.$toast.open({
              message: res.data.message,
              position: 'top',
              type: 'error',
            });
          }
        })
        .catch(err => {
          this.isLoading = false;
          this.showServerInternalError();
        });
    },
    checkManualOrder() {
      if (this.manualOrderInfo) {
        if (this.inputType == 'magento-order') {
          api
            .checkMagentoOrders({
              orders: this.manualOrderInfo,
            })
            .then(res => {
              const { success, message, data } = res.data;
              this.isLoading = false;
              if (success) {
                this.bulkInfo = data;
                this.customMagento = data;
                this.$toast.open({
                  position: 'top',
                  message,
                });
              } else {
                this.$toast.open({
                  position: 'top',
                  message,
                  type: 'error',
                });
              }
            })
            .catch(err => {
              this.isLoading = false;
              this.showServerInternalError();
            });
        } else if (this.inputType == 'ebay-order1') {
          api
            .checkEbayOrders({
              orders: this.manualOrderInfo,
            })
            .then(res => {
              const { success, message, data } = res.data;
              this.isLoading = false;
              if (success) {
                this.bulkInfo = data;
                this.customEbay = data;
                this.$toast.open({
                  position: 'top',
                  message,
                });
              } else {
                this.$toast.open({
                  position: 'top',
                  message,
                  type: 'error',
                });
              }
            })
            .catch(err => {
              this.isLoading = false;
              this.showServerInternalError();
            });
        }
      } else {
        this.$toast.open({
          position: 'top',
          message: 'Please input the orders information.',
          type: 'warning',
        });
      }
    },
    submitBulkInfo() { 
      if (this.bulkInfo) {
        this.isLoading = true;
        let orderType;
        if (
          this.inputType == 'auto-fetch' ||
          this.inputType == 'magento-order'
        ) {
          api
            .addMagentoOrdersToInventory({
              bulkInfo: this.bulkInfo,
            })
            .then(res => {
              const { success, message } = res.data;
              this.isLoading = false;
              if (success) {
                this.$toast.open({
                  position: 'top',
                  message,
                });
              } else {
                this.$toast.open({
                  position: 'top',
                  message,
                  type: 'error',
                });
              }
            })
            .catch(err => {
              this.isLoading = false;
              this.$toast.open({
                position: 'top',
                message: 'Please add valid data!',
                type: 'error',
              });
            });
        } else {
          api
            .addEbayOrdersToInventory({
              bulkInfo: this.bulkInfo,
            })
            .then(res => {
              const { success, message } = res.data;
              this.isLoading = false;
              if (success) {
                this.$toast.open({
                  position: 'top',
                  message,
                });
              } else {
                this.$toast.open({
                  position: 'top',
                  message,
                  type: 'error',
                });
              }
            })
            .catch(err => {
              this.isLoading = false;
              this.$toast.open({
                position: 'top',
                message: 'Please add valid data!',
                type: 'error',
              });
            });
        }
      } else {
        this.$toast.open({
          position: 'top',
          message: 'Please input the orders information.',
          type: 'warning',
        });
      }
    },
    showServerInternalError() {
      this.$toast.open({
        position: 'top',
        message: 'Something wrong went.',
        type: 'error',
      });
    },

    // Ebay
    fetchOrderFromEbay() {
      this.isLoading = true;
      api
        .fetchEbayOrderInfo()
        .then(res => {
          if (res.data.success) {
            setTimeout(() => {
              this.isLoading = false;
              this.bulkInfo = res.data.data;
              this.EbayOrderData = res.data.data;
              this.$toast.open({
                message: res.data.message,
                position: 'top',
              });
            }, 500);
          } else {
            this.isLoading = false;
            this.$toast.open({
              message: res.data.message,
              position: 'top',
              type: 'error',
            });
          }
        })
        .catch(err => {
          this.isLoading = false;
          this.showServerInternalError();
        });
    },
    submitOrderFromEbay() {
      this.isLoading = true;
      let obj = {
        bulkInfo: this.EbayOrderData,
      };
      api
        .addEbayOrderLive(obj)
        .then(res => {
          if (res.data.success) {
            setTimeout(() => {
              this.isLoading = false;
              console.log(res.data);
              this.$toast.open({
                message: res.data.message,
                position: 'top',
              });
            }, 500);
          } else {
            this.isLoading = false;
            this.$toast.open({
              message: res.data.message,
              position: 'top',
              type: 'error',
            });
          }
        })
        .catch(err => {
          this.isLoading = false;
          this.showServerInternalError();
        });
    },
    downloadCSV(str) {
      let JSONData = [];
      if (str == 'fromMagento') {
        JSONData = this.MagentoOrderData;
      } else if (str == 'fromEbay') {
        JSONData = this.EbayOrderData;
      } else if (str == 'fromCustomMagento') {
        JSONData = this.customMagento;
      } else if (str == 'fromCustomEbay') {
        JSONData = this.customEbay;
      }
      let ShowLabel = true;
      var arrData =
        typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;

      var CSV = '';

      if (ShowLabel) {
        var row = '';

        for (var index in arrData[0]) {
          row += index + ',';
        }

        row = row.slice(0, -1);

        CSV += row + '\r\n';
      }

      for (var i = 0; i < arrData.length; i++) {
        var row = '';

        for (var index in arrData[i]) {
          row += '"' + arrData[i][index] + '",';
        }

        row.slice(0, row.length - 1);

        CSV += row + '\r\n';
      }

      if (CSV == '') {
        alert('Invalid data');
        return;
      }

      var fileName = 'OrdersData';
      var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);

      var link = document.createElement('a');
      link.href = uri;

      link.style = 'visibility:hidden';
      link.download = fileName + '.csv';

      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
  },
};
</script>
