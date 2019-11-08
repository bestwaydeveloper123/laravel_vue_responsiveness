<template>
  <div id="main" class="mr-0" style="margin:auto">
    <!-- Ping -->
    <div id="ping" class="float-left mr-2">
      <div class="input-group float-left">
        <button @click="showPingModal()" class="btn btn-sm btn-primary" type="button">
          <i class="fa fa-commenting"></i> Ping
        </button>
      </div>
      <ping-editor-modal
        :class-name="getStyle"
        v-if="has_ping_modal"
        @save="saveChange"
        @close="closePing"
      />
    </div>

    <!-- Misc Points dropdown -->
    <div class="float-left mr-2">
      <select
        v-model="rmaForm"
        @change="showMiscPointsModal(rmaForm)"
        class="form-control form-control-sm float-right"
        style="width: 150px;"
      >
        <option value disabled selected>Misc Points</option>
        <option value="Paypal">Paypal</option>
        <option value="BBB">BBB</option>
        <option value="Case Closed">Case Closed</option>
        <option value="Negative Feedback Removed">Negative Feedback Removed</option>
        <option value="Late Shipment Removal">Late Shipment Removal</option>
      </select>
      <misc-points-modal
        :class-name="getStyle"
        :title="title"
        v-if="has_case_modal"
        @save="saveChange"
        @close="closeMiscPoints"
      />
    </div>

    <!-- Points Breakdown -->
    <div id="points_breakdown" class="float-left mr-2">
      <div class="input-group float-left">
        <button @click="showPointsBreakdownModal()" class="btn btn-sm btn-success" type="button">
          <i class="fa fa-arrow-circle-down"></i> Points Breakdown
        </button>
      </div>
      <history-modal
        :class-name="getStyle"
        v-if="has_points_modal"
        @close="closePointsBreakdown"
        :data="historyData"
        :user="userName"
      />
    </div>

    <!-- Docusign & RMAForm Button -->
    <div class="float-left">
      <li class="mr-2 float-left">
        <a href="/accounts/docusign/view" class="btn btn-sm badge-success">
          <i class="fa fa-file-text"></i> Docusign
        </a>
      </li>
      <li class="mr-2 float-left">
        <!-- href="/accounts/rmaform/create" -->
        <a @click="showRMAModal" class="btn btn-sm badge-success" style="color:white">
          <i class="fa fa-file-text"></i> RMA Form
        </a>
      </li>
    </div>
    <RMAModal
      :class-name="getStyle"
      v-if="has_rma_modal"
      @save="closeRMA"
      @close="closeRMA"
      :cusEmail="cusEmail"
    />

    <!-- Admin Emails -->
    <div id="admin-emails" class="float-left">
      <select
        v-model="adminEmailValue"
        @change="adminEmails(adminEmailValue)"
        class="form-control form-control-sm float-right"
        style="width: 150px;"
      >
        <option value disabled="disabled" selected="selected">Admin Emails</option>
        <option value="mforv">MESSAGE FOR VIN</option>
        <option value="ivin">INVALID VIN</option>
        <option value="diffv">DIFFERENT VIN</option>
        <option value="wpnpd">WRONG PART - NO PRICE DIFFERENCE</option>
        <option value="wppi">WRONG PART – PRICE INCREASE</option>
        <option value="wppd">WRONG PART – PRICE DECREASE</option>
        <option value="vpn">VERIFY PART NUMBER</option>
        <option value="dupo">DUPLICATE ORDER</option>
      </select>
    </div>
    <admin-emails-modal
      :class-name="getStyle"
      :title="title"
      :email-temp="emailTemp"
      v-if="has_admin_modal"
      @save="saveChange"
      @close="closeAdmin"
    />
    <loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
  </div>
</template>

<script>
import api from '../../api';
import SendRma from './SendRma';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import PingEditorModal from './PingEditorModal';
import MiscPointsModal from './MiscPointsModal';
import RMAModal from './RMAModal';
import HistoryModal from './HistoryModal';
import AdminEmailsModal from './AdminEmailsModal';

export default {
  name: 'BreadcrumbsMenu',

  components: {
    SendRma,
    Loading,
    PingEditorModal,
    MiscPointsModal,
    RMAModal,
    HistoryModal,
    AdminEmailsModal,
  },

  props: {
    cusEmail: String,
    userName: String,
  },

  data() {
    return {
      has_ping_modal: false,
      has_send_modal: false,
      has_case_modal: false,
      has_rma_modal: false,
      has_points_modal: false,
      has_admin_modal: false,
      rmaForm: '',
      email1: '',
      email2: '',
      isLoading: false,
      fullPage: true,
      title: '',
      historyData: [],
      adminEmailValue: '',
      emailTemp: '',
    };
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
    // Admin Emails
    adminEmails(val) {
      if (val == 'diffv') {
        this.title = 'Different VIN';
        this.emailTemp = 'diffv';
        this.has_admin_modal = true;
      } else if (val == 'wpnpd') {
        this.title = 'WRONG PART - No Price Difference';
        this.emailTemp = 'wpnpd';
        this.has_admin_modal = true;
      } else if (val == 'wppd') {
        this.title = 'WRONG PART – PRICE DECREASE';
        this.emailTemp = 'wppd';
        this.has_admin_modal = true;
      } else {
        let obj = {
          account_id: localStorage.getItem('accountId'),
          emailTemplate: val,
          wrongpart: null,
          correctvin: null,
        };
        this.isLoading = true;
        api
          .AdminEmails(obj)
          .then(res => {
            setTimeout(() => {
              this.isLoading = false;
            }, 500);
            if (res.data.IsSuccess) {
              this.$toast.open({
                message: 'Email sent successfully!',
                position: 'top',
              });
            } else {
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
      }
    },

    closeAdmin() {
      this.has_admin_modal = false;
    },

    //RMA Form download and Send
    rmaFn(val) {
      if (val == 'rmaDownload') {
        this.downloadForm();
        this.rmaForm = '';
      }
      if (val == 'rmaSend') {
        this.email1 = this.emails.email;
        this.email2 = this.emails.email2;
        this.showSendModal();
      }
    },
    downloadForm() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
      };
      api
        .RMAForm(obj)
        .then(res => {
          location.href = res.data.file;
        })
        .catch(err => console.log(err));
    },

    showRMAModal() {
      this.has_rma_modal = true;
    },

    closeRMA() {
      this.has_rma_modal = false;
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
        account_id: this.emails.account_id,
        email: val,
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
            } else {
              this.$toast.open({
                message: 'Something went wrong!',
                position: 'top-right',
                type: 'error',
              });
            }
          }
        })
        .catch(err => console.log(err));
      this.rmaForm = '';
      this.has_send_modal = false;
    },

    // Ping Module
    showPingModal() {
      this.has_ping_modal = true;
    },
    saveChange() {
      this.has_ping_modal = false;
      this.has_case_modal = false;
      this.has_admin_modal = false;
    },
    closePing() {
      this.has_ping_modal = false;
    },

    // Misc Points
    showMiscPointsModal(option) {
      if (option == 'Case Closed') {
        this.title = 'Case Closed';
      } else if (option == 'Negative Feedback Removed') {
        this.title = 'Negative Feedback Removed';
      } else if (option == 'Paypal') {
        this.title = 'Paypal';
      } else if (option == 'BBB') {
        this.title = 'BBB';
      } else if (option == 'Late Shipment Removal') {
        this.lateShipmentRemoval();
      }

      if (
        option == 'Case Closed' ||
        option == 'Negative Feedback Removed' ||
        option == 'Paypal' ||
        option == 'BBB'
      ) {
        this.has_case_modal = true;
      }
    },
    closeMiscPoints() {
      this.has_case_modal = false;
    },
    lateShipmentRemoval() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
      };
      api
        .LateShipmentRemoval(obj)
        .then(res => {
          if (res) {
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
            this.$toast.open({
              message: 'Something went wrong!',
              position: 'top-right',
              type: 'error',
            }),
            this.$emit('close')
          ),
        );
    },

    // Points Breakdown
    showPointsBreakdownModal() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
      };
      api.PointsBreakdown(obj).then(res => {
        if (res.data.success) {
          this.historyData = res.data.data;
          this.has_points_modal = true;
        }
      });
    },
    closePointsBreakdown() {
      this.has_points_modal = false;
    },
  },
};
</script>
