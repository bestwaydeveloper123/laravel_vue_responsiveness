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
                  <tr v-if="title == 'Different VIN'">
                    <td class="pt-0 pb-0" width="15%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="correct-vehicle"
                      >Correct Vehicle</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input
                        v-model="vin"
                        type="text"
                        class="form-control form-control-sm"
                        id="correct-vehicle"
                      />
                    </td>
                  </tr>
                  <tr v-if="title == 'WRONG PART - No Price Difference'">
                    <td class="pt-0 pb-0" width="15%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="correct-partnumber"
                      >Correct PartNumber</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input
                        v-model="partNumber"
                        type="text"
                        class="form-control form-control-sm"
                        id="correct-partnumber"
                      />
                    </td>
                  </tr>
                  <tr v-if="title == 'WRONG PART – PRICE DECREASE'">
                    <td class="pt-0 pb-0" width="15%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="correct-partnumber"
                      >Insert Correct PartNumber</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input
                        v-model="partNumber"
                        type="text"
                        class="form-control form-control-sm"
                        id="correct-partnumber"
                      />
                    </td>
                  </tr>
                  <tr v-if="title == 'WRONG PART – PRICE DECREASE'">
                    <td class="pt-0 pb-0" width="15%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="correct-partnumber"
                      >Insert Price Decrease</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <input
                        v-model="priceDecrease"
                        type="text"
                        class="form-control form-control-sm"
                        id="correct-partnumber"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer pt-0 pb-0 mt-1 bg-gray-300 theme-color">
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
              <button
                :disabled="!sendButton"
                class="btn btn-success"
                type="button"
                @click="saveChange"
              >Send Email</button>
            </div>
          </div>
        </div>
      </div>
      <loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
    </div>
  </transition>
</template>

<script>
import api from '../../api';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  components: {
    Loading,
  },

  props: {
    className: {
      type: String,
      default: null,
    },
    title: String,
    emailTemp: String,
  },

  data() {
    return {
      vin: '',
      partNumber: '',
      isLoading: false,
      fullPage: true,
      sendButton: false,
      priceDecrease: ''
    };
  },

  watch: {
    vin: function(val, oldVal) {
      val ? (this.sendButton = true) : (this.sendButton = false);
    },
    partNumber: function(val, oldVal) {
      val ? (this.sendButton = true) : (this.sendButton = false);
    },
  },

  methods: {
    close() {
      this.$emit('close');
    },
    saveChange() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
        emailTemplate: this.emailTemp,
        correctvin: this.vin,
        wrongpart: this.partNumber,
        pricedecrease: this.priceDecrease
      };
      this.isLoading = true;
      api
        .AdminEmails(obj)
        .then(res => {
          setTimeout(() => {
            this.isLoading = false;
          }, 500);
          if (res.data.IsSuccess) {
            this.$emit('close');
            this.$toast.open({
              message: 'Email sent successfully!',
              position: 'top',
            });
          } else {
            this.$emit('close');
            this.$toast.open({
              message: 'Something went wrong!',
              position: 'top',
              type: 'error',
            });
          }
        })
        .catch(
          err => (
            (this.isLoading = false),
            this.$toast.open({
              message: 'Something went wrong!',
              position: 'top',
              type: 'error',
            })
          ),
        );
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
