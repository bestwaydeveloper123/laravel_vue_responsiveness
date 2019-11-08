<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header py-1" :class="headerStyle">
              <i class="fa fa-car"></i> {{ HeaderLabel }}
            </div>
            <div class="modal-body p-0 my-1">
              <table class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0">
                <tr>
                  <td class="py-0" width="20%">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="direction"
                    >Direction</label>
                  </td>
                  <td class="py-0" width="80%" colspan="2">
                    <select v-model="data.direction" class="form-control form-control-sm">
                      <option>Select</option>
                      <option>OutBound</option>
                      <option>InBound</option>
                    </select>
                  </td>
                </tr>
                <tr v-if="isOutBound">
                  <td class="py-0" width="20%">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="dateshipped"
                    >Date Shipped</label>
                  </td>
                  <td class="py-0" width="80%" colspan="2">
                    <span style="float:left;width:94%;">
                      <input
                        class="form-control form-control-sm"
                        type="date"
                        id="dateshipped"
                        v-model="data.dateshippedtocustomer"
                      />
                    </span>
                    <span
                      class="btn btn-sm badge-success ml-2 py-0"
                      @click="todayDate('dateshippedtocustomer')"
                    >
                      <i class="fa fa-calendar-check-o"></i>
                    </span>
                  </td>
                </tr>
                <tr v-if="isOutBound">
                  <td class="py-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="deliverydatetocustomer"
                    >Delivery Date</label>
                  </td>
                  <td class="py-0">
                    <span style="float:left;width:94%;">
                      <input
                        class="form-control form-control-sm"
                        id="deliverydatetocustomer"
                        type="date"
                        v-model="data.deliverydatetocustomer"
                      />
                    </span>
                    <span
                      class="btn btn-sm badge-success ml-2 py-0"
                      @click="todayDate('deliverydatetocustomer')"
                    >
                      <i class="fa fa-calendar-check-o"></i>
                    </span>
                  </td>
                </tr>
                <tr v-if="isOutBound">
                  <td class="py-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="customer-trackingnumber"
                    >Customer Tracking#</label>
                  </td>
                  <td class="py-0" colspan="2">
                    <textarea
                      class="form-control form-control-sm"
                      id="customer-trackingnumber"
                      type="text"
                      v-model="data.customertrackingnumber"
                    ></textarea>
                  </td>
                </tr>
                <tr v-if="isOutBound || isInBound">
                  <td class="py-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="returntrackinginfo"
                    >Return Tracking</label>
                  </td>
                  <td class="py-0">
                    <textarea
                      class="form-control form-control-sm"
                      id="returntrackinginfo"
                      type="text"
                      v-model="data.returntrackinginfo"
                    ></textarea>
                  </td>
                </tr>
                <tr v-if="isOutBound">
                  <td class="py-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="antitheft"
                    >Anti Theft</label>
                  </td>
                  <td class="py-0">
                    <input
                      class="form-control form-control-sm"
                      id="antitheft"
                      type="text"
                      v-model="data.antitheft"
                    />
                  </td>
                </tr>
                <tr v-if="isOutBound || isInBound">
                  <td class="py-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="warrantysticker"
                    >Warranty #</label>
                  </td>
                  <td class="py-0" colspan="2">
                    <input
                      class="form-control form-control-sm"
                      id="warrantysticker"
                      type="text"
                      v-model="data.warrantysticker"
                    />
                  </td>
                </tr>
                <tr v-if="isOutBound">
                  <td class="py-0">
                    <label
                      class="col-form-label col-form-label-sm"
                    >Warranty Exhausted</label>
                  </td>
                  <td class="py-0" colspan="1">
                    <div class="custom-control custom-checkbox">
                      <input
                        class="custom-control-input"
                        id="exhausted"
                        type="checkbox"
                        v-model="data.warrantyexhausted"
                      />
                      <label
                        class="custom-control-label"
                        for="exhausted"
                      >Warranty Exhausted</label>
                    </div>
                  </td>
                </tr>
                <tr v-if="isInBound">
                  <td class="py-0" width="20%">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="return-shipping-day"
                    >Return Shipping Date</label>
                  </td>
                  <td class="py-0" width="80%" colspan="2">
                    <span style="float:left;width:94%;">
                      <input
                        class="form-control form-control-sm"
                        type="date"
                        id="returnShippingDate"
                        v-model="data.returnShippingDate"
                      />
                    </span>
                    <span
                      class="btn btn-sm badge-success ml-2 py-0"
                      @click="todayDate('returnShippingDate')"
                    >
                      <i class="fa fa-calendar-check-o"></i>
                    </span>
                  </td>
                </tr>
                <tr v-if="isInBound">
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">Unauthorized</label>
                  </td>
                  <td class="py-0" colspan="1">
                    <div class="custom-control custom-checkbox">
                      <input
                        class="custom-control-input"
                        id="unauthorized"
                        type="checkbox"
                        v-model="data.unauthorized"
                      />
                      <label
                        class="custom-control-label"
                        for="unauthorized"
                      >Unauthorized</label>
                    </div>
                  </td>
                </tr>
                <tr v-if="isInBound">
                  <td class="py-0">
                    <label
                      class="col-form-label col-form-label-sm"
                    >Customer Provide Tracking</label>
                  </td>
                  <td class="py-0" colspan="1">
                    <div class="custom-control custom-checkbox">
                      <input
                        class="custom-control-input"
                        id="customerProvideTracking"
                        type="checkbox"
                        v-model="data.customerProvideTracking"
                      />
                      <label
                        class="custom-control-label"
                        for="customerProvideTracking"
                      >Customer Provide Tracking</label>
                    </div>
                    <tr v-if="data.customerProvideTracking">
                      <td>
                        <div class="custom-control">
                          <label for="returnShippingCost">Return Shipping Cost</label>
                          <input
                            class="form-control form-control-sm"
                            id="returnShippingCost"
                            type="text"
                            v-model="data.returnShippingCost"
                          />
                        </div>
                      </td>
                    </tr>
                  </td>
                </tr>
                <tr v-if="isOutBound || isInBound">
                  <td class="py-0">
                    <label class="col-form-label col-form-label-sm">RMA</label>
                  </td>
                  <td class="py-0" colspan="1">
                    <div class="custom-control custom-checkbox">
                      <input
                        id="rma"
                        class="custom-control-input"
                        type="checkbox"
                        v-model="data.rma"
                      />
                      <label class="custom-control-label" for="rma">RMA</label>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="modal-footer bg-gray-300 theme-color py-0 mt-1">
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
              <button
                class="btn btn-success"
                type="button"
                @click="saveChange"
                :disabled="!isInBound && !isOutBound"
              >Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    trackingData: {
      index: {
        type: Number,
        default: -1,
      },
      data: {
        type: Object,
        default: {},
      },
    },
    track: String,
    headerStyle: String,
  },
  data() {
    return {
      data: {
        id: -1,
        direction: '',
        trackingcode: '',
        dateshippedtocustomer: '',
        deliverydatetocustomer: '',
        antitheft: '',
        customertrackingnumber: '',
        returntrackinginfo: '',
        warrantysticker: '',
        rma: false,
        warrantyexhausted: false,
        returnShippingDate: '',
        unauthorized: false,
        customerProvideTracking: false,
        returnShippingCost: '',
      },
    };
  },
  created() {
    const { index, data } = this.trackingData;
    if (index !== -1) {
      Object.assign(this.data, data);
    } else {
      if (this.track == '1') {
        this.data.direction = 'OutBound';
      } else if (this.track == '2') {
        this.data.direction = 'InBound';
      }
    }
  },
  computed: {
    isOutBound() {
      return this.data.direction == 'OutBound';
    },
    isInBound() {
      return this.data.direction == 'InBound';
    },
    HeaderLabel() {
      const { index } = this.trackingData;
      if (index != -1) {
        return `Tracking ${index + 1}`;
      }
      return 'Add a new Tracking';
    }
  },
  methods: {
    todayDate(key) {
      const today = new Date();
      let dd = today.getDate();
      let mm = today.getMonth() + 1; //January is 0!
      const yyyy = today.getFullYear();

      dd = dd < 10 ? `0${dd}` : dd;
      mm = mm < 10 ? `0${mm}` : mm;

      this.data[key] = `${yyyy}-${mm}-${dd}`;
    },
    close() {
      this.$emit('close');
    },
    saveChange() {
      if (this.hasChange()) {
        this.$emit('save', this.data);
      } else {
        this.$emit('close');
      }
    },
    hasChange() {
      return true;
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
