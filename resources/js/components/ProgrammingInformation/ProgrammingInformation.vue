<template>
  <div class="card mb-1">
    <div class="card-header bg-gray-700 theme-color py-1">
      <a
        class="text-light"
        data-toggle="collapse"
        href="#programmingAccordition"
        aria-expanded="true"
        aria-controls="programmingAccordition"
      >
        <strong>
          <i class="fa fa-edit"></i> Programming
          <i class="fa fa-caret-down"></i>
        </strong>
      </a>
    </div>
    <div class="card-body py-0" data-children=".item">
      <div
        :class="{'show': (userRole != 1 || userRole != 3)}"
        class="item collapse"
        id="programmingAccordition"
        role="tabpanel"
      >
        <table v-if="isEdit" class="table table-bordered table-responsive-sm table-sm mb-1" width="100%">
          <tr>
            <td class="pt-0 pb-0 font-weight-700">
              <label class="col-form-label col-form-label-sm" for="order-type1">{{ account.id }}</label>
            </td>
            <td class="pt-0 pb-0 font-weight-700">
              <label
                class="col-form-label col-form-label-sm"
                for="order-type1"
              >{{ account.ordertype }}</label>
            </td>
            <td class="pt-0 pb-0 font-weight-700">
              <label
                class="col-form-label col-form-label-sm"
                for="order-type1"
              >{{ account.datecustomerpurchased }}</label>
            </td>
            <td class="pt-0 pb-0 font-weight-700">
              <label
                class="col-form-label col-form-label-sm"
                for="order-type1"
              >{{ account.accountname }}</label>
            </td>
            <td class="pt-0 pb-0 font-weight-700">
              <label
                class="col-form-label col-form-label-sm"
                for="order-type1"
              >{{ account.primaryaccountmanager }}</label>
            </td>
          </tr>
        </table>
        <table
          id="account_table"
          class="table table-bordered table-responsive-sm table-sm mb-1"
          width="100%"
        >
          <thead></thead>
          <tbody>
            <tr>
              <td class="pt-0 pb-0">
                <label class="col-form-label col-form-label-sm" for="text-input1">Item Purchased</label>
              </td>
              <td class="pt-0 pb-0" colspan="4">
                <textarea
                  class="form-control form-control-sm bg-gray-100"
                  id="text-input1"
                  type="text"
                  rows="1"
                  readonly
                  v-model="account.itempurchased"
                ></textarea>
              </td>
            </tr>
            <tr>
              <td class="pt-0 pb-0">
                <label class="col-form-label col-form-label-sm" for="text-input">Customer VIN</label>
              </td>
              <td class="pt-0 pb-0" colspan="2">
                <div class="input-group">
                  <input
                    class="form-control form-control-sm bg-gray-100"
                    id="customervin"
                    type="text"
                    readonly
                    v-model="account.customervin"
                  />
                  <span v-if="isEdit" class="input-group-append">
                    <button
                      class="btn btn-sm btn-secondary text-light bg-info"
                      type="button"
                      onclick="var copyText=document.getElementById('customervin'); copyText.select(); document.execCommand('copy');"
                    >copy</button>
                  </span>
                </div>
              </td>
              <td class="pt-0 pb-0">
                <label class="col-form-label col-form-label-sm" for="hardware">Hardware</label>
              </td>
              <td class="pt-0 pb-0">
                <input
                  onkeyup="changeHardwareValue();"
                  class="form-control form-control-sm bg-gray-100"
                  id="hardware"
                  type="text"
                  v-model="account.pcmhardwaretype"
                />
              </td>
            </tr>
            <tr>
              <td class="pt-0 pb-0">
                <label v-if="isEdit" class="col-form-label col-form-label-sm" for="text-input">Part Location</label>
              </td>
              <td class="pt-0 pb-0" colspan="2">
                <div v-if="isEdit" class="input-group">
                  <part-location
                    :account-team="account.accountteam"
                    :role-name="roleName"
                    :account-id="account.id"
                  ></part-location>
                </div>
              </td>
              <td class="pt-0 pb-0">
                <label class="col-form-label col-form-label-sm" for="prog-notes-2">Prog Notes</label>
              </td>
              <td class="pt-0 pb-0">
                <textarea
                  onkeyup="pNoteValueChange2();"
                  class="form-control form-control-sm bg-gray-100"
                  id="prog-notes-2"
                  rows="1"
                  v-model="account.programmingdetails"
                ></textarea>
              </td>
            </tr>
          </tbody>
        </table>
        <attribute-card v-if="isEdit" :account="account" :customer="customermetadata"></attribute-card>
        <programming-entry
          :pg-entries="pg_entries"
          :role="userRole"
          :account-id="account_id"
          :on-board-value="onboard_testing"
          :is-edit="isEdit"
        ></programming-entry>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../api';

export default {
  name: 'ProgrammingInformation',
  props: {
    accountData: String,
    userRole: String,
    customerData: String,
    roleName: String,
    pgEntries: String,
    isEdit: Boolean,
  },
  data() {
    return {
      account: this.accountData ? JSON.parse(this.accountData) : {},
      customermetadata: this.customerData ? JSON.parse(this.customerData) : {},
      pg_entries: this.pgEntries ? JSON.parse(this.pgEntries) : [],
      account_id: '',
      onboard_testing: '',
    };
  },
  mounted() {
    var id = this.account ? this.account.id : null;
    this.account_id = id ? id.toString() : '';
    var onboard = this.account ? this.account.onboard_testing : null;
    this.onboard_testing = onboard ? onboard.toString() : '';
  },
  methods: {},
};
</script>
