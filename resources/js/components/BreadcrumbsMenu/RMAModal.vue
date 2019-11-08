<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header pt-1 pb-1" :class="className">
              <i class="fa fa-file-text"></i> RMA Form
            </div>
            <div class="modal-body pt-0 pb-0 pl-0 pr-0 mt-1 mb-1">
              <table
                class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
                width="2/3"
              >
                <tbody>
                  <tr>
                    <td class="pt-0 pb-0" width="10%">
                      <label class="col-form-label col-form-label-sm" for="text-input">RMA Download</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <select
                        class="form-control form-control-sm"
                        width="100%"
                        type="text"
                        v-model="dowbloadRMAid"
                        @change="rmaFn('download')"
                      >
                        <option disabled value selected>Select</option>
                        <option
                          v-for="(rma) in allRMA"
                          :key="rma.id"
                          :value="rma.id"
                          v-if="rma.customer_signature"
                        >{{rma.created_at}}</option>
                      </select>
                    </td>
                  </tr>
                  <!-- <tr>
                    <td class="pt-0 pb-0" width="10%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="text-input"
                      >Send RMA To Customer</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <select
                        class="form-control form-control-sm"
                        width="100%"
                        type="text"
                        v-model="sendRMAid"
                        @change="rmaFn('rmaSend')"
                      >
                        <option disabled value selected>Select</option>
                        <option
                          v-for="(rma) in allRMA"
                          :key="rma.id"
                          :value="rma.id"
                        >{{rma.created_at}}</option>
                      </select>
                    </td>
                  </tr>-->
                  <tr>
                    <td class="pt-0 pb-0" width="10%">
                      <label class="col-form-label col-form-label-sm" for="text-input">View RMA</label>
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <select
                        class="form-control form-control-sm"
                        width="100%"
                        type="text"
                        v-model="viewRMAid"
                        @change="rmaFn('view')"
                      >
                        <option disabled value selected>Select</option>
                        <option
                          v-for="(rma) in allRMA"
                          v-if="rma.customer_signature"
                          :key="rma.id"
                          :value="rma.id"
                        >{{rma.created_at}}</option>
                      </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer pt-0 pb-0 mt-1 bg-gray-300 theme-color">
              <button
                @click="downloadRMAForm()"
                type="button"
                class="btn btn-primary"
              >Download Blank RMA</button>
              <button
                @click="sendBlankRMAForm()"
                type="button"
                class="btn btn-success"
              >Send RMA Form</button>
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
              <!-- <button class="btn btn-success" type="button" @click="saveChange">
                Send
              </button>-->
            </div>
          </div>
        </div>
      </div>
      <send-rma
        :class-name="getStyle"
        v-if="has_send_modal"
        :email1="email1"
        :email2="email2"
        @save="sendMail"
        @close="closeSend"
      />
      <loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
    </div>
  </transition>
</template>

<script>
import api from '../../api';
import SendRma from './SendRma';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  components: {
    SendRma,
    Loading,
  },

  props: {
    className: {
      type: String,
      default: null,
    },
    cusEmail: String,
  },

  data() {
    return {
      allRMA: '',
      dowbloadRMAid: '',
      viewRMAid: '',
      sendRMAid: '',
      has_send_modal: false,
      isLoading: false,
      fullPage: true,
      email1: '',
      email2: '',
      rmaData: '',
    };
  },

  mounted() {
    this.getRMAFormdata();
  },

  computed: {
    emails() {
      return JSON.parse(this.cusEmail);
    },
    getStyle() {
      return (
        (this.headerColor || 'bg-gray-700 theme-color ') +
        (this.headerTextColor || 'text-light')
      );
    },
  },

  methods: {
    rmaFn(str) {
      if (str == 'download') {
        this.downloadForm();
      }
      if (str == 'rmaSend') {
        this.email1 = this.emails.email;
        this.email2 = this.emails.email2;
        this.showSendModal();
      }
      if (str == 'view') {
        window.location.href =
          '/accounts/rmaform/create/' + localStorage.getItem('accountId') + '/' + this.viewRMAid;
      }
    },
    showSendModal() {
      this.has_send_modal = true;
    },
    closeSend() {
      this.rmaForm = '';
      this.has_send_modal = false;
    },
    sendMail(val) {
      let obj = {
        account_id: localStorage.getItem('accountId'),
        email: val,
        form_id: this.sendRMAid,
      };
      this.isLoading = true;
      api
        .RMAFormSendFromEmail(obj)
        .then(res => {
          if (res) {
            setTimeout(() => {
              this.isLoading = false;
            }, 500);
            if (res.data.IsSuccess) {
              this.$toast.open({
                message: 'RMA is successfully sent to your email!',
                position: 'top-right',
              });
              this.$emit('close');
            } else {
              this.$toast.open({
                message: 'Something went wrong!',
                position: 'top-right',
                type: 'error',
              });
            }
          }
        })
        .catch(
          err => (
            (this.isLoading = false),
            this.$toast.open({
              message: 'Something went wrong!',
              position: 'top-right',
              type: 'error',
            })
          ),
        );
      this.rmaForm = '';
      this.has_send_modal = false;
    },
    downloadForm() {
      axios
        .post(`/api/RmaPdfDownload`, {
          account_id: localStorage.getItem('accountId'),
          form_id: this.dowbloadRMAid,
        })
        .then(response => {
          let pdf_url = response.data;
          var xhr = new XMLHttpRequest();
            xhr.open("GET", pdf_url, true);
            xhr.responseType = 'blob';
            xhr.onload = function () {
              let a = document.createElement('a');
              a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
              a.download = "RMAForm"; // Set the file name.
              a.style.display = 'none';
              document.body.appendChild(a);
              a.click();
            };
            xhr.send();
        });
    },
    close() {
      this.$emit('close');
    },
    getRMAFormdata() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
      };
      api
        .GetRmaFormsList(obj)
        .then(res => {
          if (res.data.IsSuccess) {
            this.allRMA = res.data.Data;
          }
        })
        .catch(err => {});
    },
    sendBlankRMAForm() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
      };
      this.isLoading = true;
      api
        .SendRmaFormEmail(obj)
        .then(res => {
          if (res) {
            setTimeout(() => {
              this.isLoading = false;
            }, 500);
            if (res.data.IsSuccess) {
              this.$toast.open({
                message: res.data.Message,
                position: 'top',
              });
              this.$emit('close');
            } else {
              this.$toast.open({
                message: res.data.Message,
                position: 'top',
                type: 'error',
              });
              this.$emit('close');
            }
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
    downloadRMAForm() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
      };
      api
        .RMAFormDownload(obj)
        .then(res => {
          location.href = res.data.file;
        })
        .catch(err => console.log(err));
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
