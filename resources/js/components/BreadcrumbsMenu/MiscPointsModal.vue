<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header pt-1 pb-1" :class="className">
              <i class="fa fa-edit"></i>
              {{title}}
            </div>
            <div class="modal-body pt-0 pb-0 pl-0 pr-0 mt-1 mb-1">
              <table
                class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
                width="2/3"
              >
                <tbody>
                  <tr v-if="title != 'Negative Feedback Removed' && title != 'Paypal' && title != 'BBB'">
                    <td class="pt-0 pb-0" width="10%">
                      <label class="col-form-label col-form-label-sm" for="sale-price">Sale Price</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input v-model="salePrice" type="text" class="form-control form-control-sm" id="sale-price" />
                    </td>
                  </tr>
                  <tr v-if="title != 'Negative Feedback Removed' && title == 'Paypal'">
                    <td class="pt-0 pb-0" width="10%">
                      <label class="col-form-label col-form-label-sm" for="price-paid">Price Paid</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input
                        v-model="pricePaid"
                        type="text"
                        class="form-control form-control-sm"
                        id="price-paid"
                      />
                    </td>
                  </tr>
                  <tr v-if="title != 'Negative Feedback Removed' && title == 'Paypal'">
                    <td class="pt-0 pb-0" width="10%">
                      <label class="col-form-label col-form-label-sm" for="paypal-id">PayPal ID</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input
                        v-model="paypalId"
                        type="text"
                        class="form-control form-control-sm"
                        id="paypal-id"
                      />
                    </td>
                  </tr>
                  <tr v-if="title != 'Paypal' && title != 'BBB'">
                    <td class="pt-0 pb-0" width="10%">
                      <label class="col-form-label col-form-label-sm" for="sr">SR#</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input v-model="srNumber" type="text" class="form-control form-control-sm" id="sr" />
                    </td>
                  </tr>
                  <tr v-if="title == 'Negative Feedback Removed'">
                    <td colspan="2">*2000 Points</td>
                  </tr>
                  <tr v-if="title == 'BBB'">
                    <td colspan="2">2000 Points</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer pt-0 pb-0 mt-1 bg-gray-300 theme-color">
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
              <button class="btn btn-success" type="button" @click="saveChange">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import api from '../../api';

export default {
  props: {
    className: {
      type: String,
      default: null,
    },
    title: String,
  },

  data() {
    return {
      paypalId: '',
      pricePaid: '',
      salePrice: '',
      srNumber: ''
    };
  },

  computed: {},

  methods: {
    close() {
      this.$emit('close');
    },
    saveChange() {
      if (this.title == 'Paypal') {
        let obj = {
          account_id: localStorage.getItem('accountId'),
          paypal_id: this.paypalId,
          price_paid: this.pricePaid,
        };
        api
          .PaypalPoints(obj)
          .then(res => {
            if (res) {
              if (res.data.IsSuccess) {
                this.$toast.open({
                  message: 'Success!',
                  position: 'top-right',
                });
                this.$emit('close');
              } else {
                this.$toast.open({
                  message: 'Something went wrong!',
                  position: 'top-right',
                  type: 'error',
                });
                this.$emit('close');
              }
            }
          })
          .catch(
            err => (
              this.$toast.open({
                message: 'Something went wrong!',
                position: 'top-right',
                type: 'error',
              }),
              this.$emit('close')
            ),
          );
      } else if (this.title == 'Case Closed') {
        let obj = {
          account_id: localStorage.getItem('accountId'),
          price_paid: this.salePrice,
          sr_number: this.srNumber,
        };
        api
          .CaseClosedPoints(obj)
          .then(res => {
            if (res) {
              if (res.data.IsSuccess) {
                this.$toast.open({
                  message: 'Success!',
                  position: 'top-right',
                });
                this.$emit('close');
              } else {
                this.$toast.open({
                  message: 'Something went wrong!',
                  position: 'top-right',
                  type: 'error',
                });
                this.$emit('close');
              }
            }
          })
          .catch(
            err => (
              this.$toast.open({
                message: 'Something went wrong!',
                position: 'top-right',
                type: 'error',
              }),
              this.$emit('close')
            ),
          );
      } else if (this.title == 'Negative Feedback Removed') {
        let obj = {
          account_id: localStorage.getItem('accountId'),
          sr_number: this.srNumber,
        };
        api
          .NegativeFeedbackRemovedPoints(obj)
          .then(res => {
            if (res) {
              if (res.data.IsSuccess) {
                this.$toast.open({
                  message: 'Success!',
                  position: 'top-right',
                });
                this.$emit('close');
              } else {
                this.$toast.open({
                  message: 'Something went wrong!',
                  position: 'top-right',
                  type: 'error',
                });
                this.$emit('close');
              }
            }
          })
          .catch(
            err => (
              this.$toast.open({
                message: 'Something went wrong!',
                position: 'top-right',
                type: 'error',
              }),
              this.$emit('close')
            ),
          );
      } else if (this.title == 'BBB') {
        let obj = {
          account_id: localStorage.getItem('accountId'),
        };
        api
          .BBBPoints(obj)
          .then(res => {
            if (res) {
              if (res.data.IsSuccess) {
                this.$toast.open({
                  message: 'Success!',
                  position: 'top-right',
                });
                this.$emit('close');
              } else {
                this.$toast.open({
                  message: 'Something went wrong!',
                  position: 'top-right',
                  type: 'error',
                });
                this.$emit('close');
              }
            }
          })
          .catch(
            err => (
              this.$toast.open({
                message: 'Something went wrong!',
                position: 'top-right',
                type: 'error',
              }),
              this.$emit('close')
            ),
          );
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
  width: 35%;
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
