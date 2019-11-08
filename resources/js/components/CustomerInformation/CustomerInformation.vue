<template>
  <div class="card mb-1">
    <div class="card-header pt-1 pb-1 bg-gray-700 theme-color">
      <a
        class="text-light"
        data-toggle="collapse"
        data-parent="#exampleAccordion"
        href="#exampleAccordion1"
        aria-expanded="true"
        aria-controls="exampleAccordion1"
      >
        <strong>
          <i class="fa fa-edit"></i> Customer Information
          <i class="fa fa-caret-down"></i>
        </strong>
      </a>
    </div>
    <div class="card-body pb-0 pt-0 pl-0 pr-0">
      <div id="exampleAccordion" data-children=".item">
        <div class="item">
          <div
            :class="{'show': userRole != 3}"
            class="collapse table-responsive"
            id="exampleAccordion1"
            role="tabpanel"
          >
            <table
              id="account_table"
              class="table table-bordered table-responsive-sm table-sm pull-left mb-1"
              width="2/3"
            >
              <thead></thead>
              <tbody>
                <tr>
                  <td class="pt-0 pb-0" width="11%">
                    <label class="col-form-label col-form-label-sm" for="text-input">Account Name</label>
                  </td>
                  <td class="pt-0 pb-0" width="22%">
                    <textarea
                      class="form-control form-control-sm"
                      id="accountname"
                      type="text"
                      name="accountname"
                      placeholder
                      rows="1"
                      v-model="account.accountname"
                    ></textarea>
                  </td>
                  <td class="pt-0 pb-0" width="11%">
                    <label class="col-form-label col-form-label-sm" for="accountteam">Team</label>
                  </td>
                  <td class="pt-0 pb-0" width="22%">
                    <select
                      class="form-control form-control-sm"
                      id="accountteam"
                      type="text"
                      name="accountteam"
                      v-model="account.accountteam"
                    >
                      <option :value="account.accountteam">{{ account.accountteam }}</option>
                      <option value="Admin Team">Admin Team</option>
                      <option value="Green Team">Green Team</option>
                      <option value="Pink Team">Pink Team</option>
                      <option value="Purple Team">Purple Team</option>
                      <option value="Gold Team">Gold Team</option>
                      <option value="Orange Team">Orange Team</option>
                    </select>
                  </td>
                  <td class="pt-0 pb-0" width="11%">
                    <label class="col-form-label col-form-label-sm" for="id">H#</label>
                  </td>
                  <td class="pt-0 pb-0" width="22%">
                    <input
                      class="form-control form-control-sm bg-gray-100"
                      id="id"
                      type="text"
                      name="accounthnumber"
                      readonly
                      v-model="account.id"
                    />
                  </td>
                </tr>
                <tr>
                  <td class="pt-0 pb-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="customer-level-1"
                    >Account Type</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <select
                      class="form-control form-control-sm"
                      id="customer-level-1"
                      name="customerLevel"
                      type="text"
                    >
                      <option
                        v-if="customermetadata.level"
                        :value="customermetadata.level"
                        selected
                      >{{customermetadata.level}}</option>
                      <option value="Normal">Normal</option>
                      <option value="Plus (2+ sales)">Plus (2+ sales)</option>
                      <option value="Premier (3+ sales)">Premier (3+ sales)</option>
                      <option value="Platinum (5+ sales)">Platinum (5+ sales)</option>
                    </select>
                  </td>
                  <td class="pt-0 pb-0" width="11%">
                    <label class="col-form-label col-form-label-sm" for="text-input">VIN</label>
                  </td>
                  <td class="pt-0 pb-0" width="22%">
                    <div class="input-group">
                      <textarea
                        class="form-control form-control-sm"
                        onkeyup="vinValueChange1();"
                        @keyup="vinCheck"
                        id="customervin1"
                        type="text"
                        name="customervin"
                        placeholder
                        rows="1"
                        v-model="account.customervin"
                      ></textarea>
                      <span class="input-group-append d-none" id="customervinicon">
                        <button
                          class="btn btn-sm btn-secondary text-light bg-success"
                          type="button"
                        >
                          <i class="fa fa-check"></i>
                        </button>
                      </span>
                    </div>
                  </td>
                  <td class="pt-0 pb-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="primaryaccountmanager"
                    >Primary Manager</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <select
                      class="form-control form-control-sm"
                      id="primaryaccountmanager"
                      type="text"
                      name="primaryaccountmanager"
                    >
                      <option
                        v-if="account.primaryaccountmanager"
                        :value="account.primaryaccountmanager"
                      >{{ account.primaryaccountmanager }}</option>
                      <option value>-- None --</option>
                      <option
                        v-for="user in users"
                        :key="user.id"
                        :value="user.username"
                      >{{ user.username }} - {{ user.email }}</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td class="pt-0 pb-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="text-input"
                    >Customer Tracking#</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <textarea
                      class="form-control form-control-sm"
                      id="trackingnumbertocustomer"
                      type="text"
                      name="trackingnumbertocustomer"
                      rows="1"
                      readonly
                      v-model="customerTrackingNumber"
                    ></textarea>
                    <small>Date Shipped:</small>
                    <small>{{ shippedDate }}</small>
                  </td>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm">Secondary Manager Points</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <secondary-manager-points
                      :is-points="IsPoints"
                      :account-id="account?account.id:null"
                      is-edit
                    />
                  </td>
                  <td class="pt-0 pb-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="secondaryaccountmanager"
                    >Secondary Manager</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <select
                      class="form-control form-control-sm"
                      id="secondaryaccountmanager"
                      type="text"
                      name="secondaryaccountmanager"
                    >
                      <option
                        v-if="account.secondaryaccountmanager"
                        :value="account.secondaryaccountmanager"
                      >{{ account.secondaryaccountmanager }}</option>
                      <option value>-- None --</option>
                      <option
                        v-for="user in users"
                        :key="user.id"
                        :value="user.username"
                      >{{ user.username }} - {{ user.email }}</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="col-sm-6 col-md-4 pull-left">
              <address-edit-card
                :customer-data="customermetadata"
                :account-id="account_id"
                id="address-edit-card"
              />
            </div>
            <div class="col-sm-6 col-md-8 pull-left">
              <price-information :account="account" :customer="customermetadata" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <VINModal
      v-if="showVINModal"
      :header-style="getHeaderStyle"
      :account-id="vin_account_id"
      @close="close"
    />
  </div>
</template>

<script>
import api from '../../api';
import VINModal from './VINModal';

export default {
  name: 'CustomerInformation',
  components: {
    VINModal
  },
  props: {
    accountData: String,
    userRole: String,
    customerData: String,
    accountUsers: String,
    trackingNumber: String,
    IsPoints: String,
    shippedDate: String,
  },
  data() {
    return {
      account: this.accountData ? JSON.parse(this.accountData) : {},
      customermetadata: this.customerData ? JSON.parse(this.customerData) : {},
      users: this.accountUsers ? JSON.parse(this.accountUsers) : [],
      customerTrackingNumber: this.trackingNumber ? this.trackingNumber : '',
      account_id: '',
      showVINModal: false,
      vin_account_id: ''
    };
  },
  mounted() {
    var id = this.account ? this.account.id : null;
    this.account_id = id ? id.toString() : '';
  },
  computed: {
    getHeaderStyle() {
      return this.headerStyle || 'bg-gray-700 theme-color text-light';
    },
  },
  methods: {
    vinCheck() {
      let obj = {
        customervin: document.getElementById('customervin1').value,
        account_id: this.account.id,
      }
      api
        .customerVINMatches(obj)
        .then(res => {
          if(res.data.IsSuccess){
            this.vin_account_id = res.data.Data.id;
            this.showVINModal = true;
          }
        })
        .catch(err => {
        });
    },
    close() {
      this.showVINModal = false;
    },
  },
};
</script>
