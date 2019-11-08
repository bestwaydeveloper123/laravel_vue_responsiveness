<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header pt-1 pb-1" :class="className">
              <i class="fa fa-car"></i>Order Status
            </div>
            <div class="modal-body pt-0 pb-0 pl-0 pr-0 mt-1 mb-1">
              <table
                class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
                width="2/3"
              >
                <tbody>
                  <tr>
                    <td class="pt-0 pb-0" width="10%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="text-input"
                        >General</label
                      >
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <select
                        class="form-control form-control-sm"
                        width="100%"
                        type="text"
                        v-model="status.general"
                      >
                        <option value></option>
                        <option value="1) In Stock (Include Stock#)"
                          >1) In Stock (Include Stock#)</option
                        >
                        <option
                          value="2) Purchased by FS, waiting for shipment from vendor"
                          >2) Purchased by FS, waiting for shipment from
                          vendor</option
                        >
                        <option value="3) Shipped to FS(programming or reship)"
                          >3) Shipped to FS(programming or reship)</option
                        >
                        <option
                          value="4) Waiting on VIN or order info from customer"
                          >4) Waiting on VIN or order info from customer</option
                        >
                        <option value="5) Programming and/or packaging"
                          >5) Programming and/or packaging</option
                        >
                        <option value="6) Delivered to customer"
                          >6) Delivered to customer</option
                        >
                        <option value="7) Returning to vendor for refund"
                          >7) Returning to vendor for refund</option
                        >
                        <option value="8) Returning to vendor for replacement"
                          >8) Returning to vendor for replacement</option
                        >
                        <option value="9) Customer returning for testing"
                          >9) Customer returning for testing</option
                        >
                        <option value="10) Customer returning for refund"
                          >10) Customer returning for refund</option
                        >
                        <option value="11) Customer returning for replacement"
                          >11) Customer returning for replacement</option
                        >
                        <option value="12) Returning item to customer"
                          >12) Returning item to customer</option
                        >
                        <option
                          value="13) Replacing for customer(PUT NEW FS NUMBER IN THE NOTES)"
                          >13) Replacing for customer(PUT NEW FS NUMBER IN THE
                          NOTES)</option
                        >
                        <option value="14) Testing for customer"
                          >14) Testing for customer</option
                        >
                        <option value="15) Customer refunded"
                          >15) Customer refunded</option
                        >
                        <option value="16) Cancelled by customer"
                          >16) Cancelled by customer</option
                        >
                        <option
                          value="17) Returning through EBay or AMAZON or PAYPAL"
                          >17) Returning through EBay or AMAZON or
                          PAYPAL</option
                        >
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td class="pt-0 pb-0" width="10%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="text-custom"
                        >Custom</label
                      >
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input
                        class="form-control form-control-sm"
                        id="text-custom"
                        type="text"
                        v-model="status.custom"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer pt-0 pb-0 mt-1 bg-gray-300 theme-color">
              <button class="btn btn-secondary" type="button" @click="close">
                Close
              </button>
              <button class="btn btn-success" type="button" @click="saveChange">
                Save changes
              </button>
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
    className: {
      type: String,
      default: null,
    },
    roleName: {
      type: String,
    },
  },

  data() {
    return {
      status: {
        general: null,
        custom: null,
        rolename: this.roleName,
      },
    };
  },

  computed: {
    hasChange() {
      return this.status.custom || this.status.general;
    },
  },

  methods: {
    close() {
      this.$emit('close');
    },

    saveChange() {
      if (this.hasChange) {
        this.$emit('save', this.status);
      } else {
        this.$emit('close');
      }
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

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

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
