<template>
  <div class="card mb-1">
    <div class="card-header card-header pt-1 pb-1 bg-gray-700 theme-color">
      <a
        class="text-light"
        data-toggle="collapse"
        data-parent="#exampleAccordion"
        href="#exampleAccordion2"
        aria-expanded="true"
        aria-controls="exampleAccordion2"
      >
        <strong>
          <i class="fa fa-edit"></i> Order Information
          <i class="fa fa-caret-down"></i>
        </strong>
      </a>
    </div>
    <div class="card-body pt-0 pb-0">
      <div id="exampleAccordion" data-children=".item">
        <div class="item">
          <div
            :class="{'show': (userRole != 3 && userRole != 4)}"
            class="collapse table-responsive"
            id="exampleAccordion2"
            role="tabpanel"
          >
            <table id="account_table" class="table table-responsive-sm table-sm mb-1" width="100%">
              <thead></thead>
              <tbody>
                <tr>
                  <td class="pt-0 pb-0" width="11%">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="date-purchased-ctrl"
                    >Date Purchased</label>
                  </td>
                  <td class="pt-0 pb-0" width="22%">
                    <span style="float:left;width:auto;">
                      <input
                        class="form-control form-control-sm"
                        id="date-purchased-ctrl"
                        type="date"
                        name="datecustomerpurchased"
                        v-model="account.datecustomerpurchased"
                      />
                    </span>
                    <span class="btn btn-sm badge-success ml-2 pt-0 pb-0" id="purtodayDate">
                      <i class="fa fa-calendar-check-o"></i>
                    </span>
                  </td>
                  <td class="pt-0 pb-0" width="11%">
                    <label class="col-form-label col-form-label-sm" for="text-input">VIN</label>
                  </td>
                  <td class="pt-0 pb-0" width="22%">
                    <div class="input-group">
                      <textarea
                        class="form-control form-control-sm"
                        onkeyup="vinValueChange2();"
                        id="customervin2"
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
                  <td class="pt-0 pb-0" width="11%">
                    <label class="col-form-label col-form-label-sm" for="text-input">Ebay Username</label>
                  </td>
                  <td class="pt-0 pb-0" width="22%">
                    <div class="input-group">
                      <input
                        class="form-control form-control-sm"
                        id="ebayusername"
                        type="text"
                        name="ebayusername"
                        placeholder
                        v-model="account.ebayusername"
                      />
                      <span v-if="isEdit" class="input-group-append" id="ebayusernamebutton">
                        <a :href="'https://www.ebay.com/usr/' + account.ebayusername">
                          <button class="btn btn-sm btn-success text-light" type="button">Msg!</button>
                        </a>
                      </span>
                      <span v-if="isEdit" class="input-group-append" id="ebayusernamebuttontab">
                        <a
                          :href="'https://www.ebay.com/usr/' + account.ebayusername"
                          target="_blank"
                        >
                          <button class="btn btn-sm btn-primary text-light ml-1" type="button">Tab</button>
                        </a>
                      </span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="pt-0 pb-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="text-input"
                    >Required DelivDay</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <span style="float:left;width:auto;">
                      <input
                        class="form-control form-control-sm"
                        id="requireddeliverydate"
                        type="date"
                        name="requireddeliverydate"
                        placeholder
                        v-model="account.requireddeliverydate"
                      />
                    </span>
                    <span class="btn btn-sm badge-success ml-2 pt-0 pb-0" id="deltodayDate">
                      <i class="fa fa-calendar-check-o"></i>
                    </span>
                  </td>
                  <td class="pt-0 pb-0">
                    <label
                      class="col-form-label col-form-label-sm"
                      for="contestmultiplier"
                    >Contest Multiplier</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <select
                      class="form-control form-control-sm"
                      id="contestmultiplier"
                      type="text"
                      name="contestmultiplier"
                    >
                      <option :value="account.contestmultiplier">{{ account.contestmultiplier }}</option>
                      <option value="1">x1</option>
                      <option value="2">x2</option>
                      <option value="3">x3</option>
                      <option value="4">x4</option>
                      <option value="5">x5</option>
                      <option value="6">x6</option>
                    </select>
                  </td>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm" for="text-input">Sales Record</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <input
                      class="form-control form-control-sm"
                      id="salesrecord"
                      type="text"
                      name="salesrecord"
                      placeholder
                      v-model="account.salesrecord"
                    />
                  </td>
                </tr>
                <tr>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm">Order Type</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <select
                      class="form-control form-control-sm"
                      name="ordertype"
                      id="order-type-ctrl2"
                    >
                      <option
                        v-if="account.ordertype"
                        :value="account.ordertype"
                      >{{ account.ordertype }}</option>
                      <option v-else value>--None Selected--</option>
                      <option value="eBay- fs1inc">eBay- fs1inc</option>
                      <option value="Website">Website</option>
                      <option value="Phone">Phone</option>
                      <option value="Quote">Quote</option>
                      <option value="Stock">Stock</option>
                      <option value="Lead">Lead</option>
                      <option value="Paypal">Paypal</option>
                      <option value="eBay - other Store">eBay - other Store</option>
                      <option value="Phone - Pickup">Phone - Pickup</option>
                    </select>
                  </td>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm" for="docusign">Docusign</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <select id="docusign" class="form-control form-control-sm" name="docusign">
                      <option
                        v-if="account.docusign"
                        :value="account.docusign"
                      >{{ account.docusign }}</option>
                      <option v-else value>-- None --</option>
                      <option value="Docusign sent but not signed">Docusign sent but not signed</option>
                      <option
                        value="Signed original purchase receipt"
                      >Signed original purchase receipt</option>
                      <option value="Signed return RMA form">Signed return RMA form</option>
                      <option value="Signed both">Signed both</option>
                      <option
                        value="Other(put information in notes)"
                      >Other(put information in notes)</option>
                      <option value="Docusign not sent yet">Docusign not sent yet</option>
                    </select>
                  </td>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm" for="magento_id">Magento ID</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <input
                      class="form-control form-control-sm"
                      id="magento_id"
                      type="text"
                      name="magento_id"
                      v-model="account.magento_id"
                    />
                  </td>
                </tr>
                <tr v-if="isEdit">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="pt-0 pb-0">
                    <label class="col-form-label col-form-label-sm" for="ebayorder_id">Ebay ID</label>
                  </td>
                  <td class="pt-0 pb-0">
                    <input
                      class="form-control form-control-sm"
                      id="ebayorder_id"
                      type="text"
                      name="ebayorder_id"
                      v-model="account.ebayorder_id"
                    />
                  </td>
                </tr>
              </tbody>
            </table>

            <stock-information :stock-info="stockInformation" :order-type="account.ordertype"></stock-information>
            <attribute-card v-if="isEdit" :account="account" :customer="customermetadata"></attribute-card>

            <div class="row">
              <div class="col-sm-6 col-md-8 pull-left pl-0 pr-0">
                <div class="col-sm-12 col-md-12 pull-left">
                  <part-information
                    :customer-vin="account.customervin"
                    :role="userRole"
                    :account-id="account_id"
                    :on-board-value="onboard_testing"
                    :item="account.itempurchased"
                    :pcm="account.pcmhardwaretype"
                    :com="account.computertype"
                    :details="account.programmingdetails"
                    :price="account.pricepaid"
                  />
                </div>
                <div class="col-sm-12 col-md-12 pull-left">
                  <order-status
                    :role-name="roleName"
                    :data="order_statuses"
                    :account-team="account.accountteam"
                    :account-id="account.id"
                    :user-name="userName"
                    :is-edit="isEdit"
                  />
                </div>
              </div>
              <div class="col-sm-6 col-md-4 pull-left">
                <div class="card mb-1">
                  <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
                    <strong>
                      <i class="fa fa-user"></i> FS1 Sales People
                    </strong>
                  </div>
                  <div class="card-body pt-0 pb-0 pr-0 pl-0">
                    <table class="table table-responsive-sm table-sm pull-left mb-0">
                      <tr>
                        <td class="pt-0 pb-0">
                          <label
                            class="col-form-label col-form-label-sm"
                            for="whomadethesale"
                          >Who Made Sale</label>
                        </td>
                        <td class="pt-0 pb-0">
                          <select
                            class="form-control form-control-sm"
                            name="whomadethesale"
                            id="whomadethesale"
                          >
                            <option
                              v-if="account.whomadethesale"
                              :value="account.whomadethesale"
                            >{{ account.whomadethesale }}</option>
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
                            for="secondarysale"
                          >Secondary Sale</label>
                        </td>
                        <td class="pt-0 pb-0">
                          <select
                            class="form-control form-control-sm"
                            id="secondarysale"
                            name="secondarysale"
                          >
                            <option
                              v-if="account.secondarysale"
                              :value="account.secondarysale"
                            >{{ account.secondarysale }}</option>
                            <option value>-- None --</option>
                            <option
                              v-for="user in users"
                              :key="user.id"
                              :value="user.username"
                            >{{ user.username }} - {{ user.email }}</option>
                          </select>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="card mb-1">
                  <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
                    <strong>
                      <i class="fa fa-wrench"></i> Options
                    </strong>
                  </div>
                  <div class="card-body pt-0 pb-1">
                    <table>
                      <thead></thead>
                      <tbody>
                        <tr>
                          <td>
                            <label
                              class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
                            >
                              <input
                                class="switch-input"
                                type="hidden"
                                name="onedayhandling"
                                value="0"
                              />
                              <input
                                class="switch-input"
                                type="checkbox"
                                id="onedayhandling-1"
                                name="onedayhandling"
                                value="1"
                                :checked="account.onedayhandling == 1"
                              />
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                          </td>
                          <td class="pb-0 pt-0">1 Day Handling</td>
                        </tr>

                        <tr>
                          <td>
                            <label
                              class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
                            >
                              <input class="switch-input" type="hidden" name="sticker" value="0" />
                              <input
                                class="switch-input"
                                type="checkbox"
                                id="sticker-1"
                                name="sticker"
                                value="1"
                                :checked="account.sticker == 1"
                              />
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                          </td>
                          <td>Sticker</td>
                        </tr>
                        <tr>
                          <td>
                            <label
                              class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
                            >
                              <input
                                class="switch-input"
                                type="hidden"
                                name="fixplugorcase"
                                value="0"
                              />
                              <input
                                class="switch-input"
                                type="checkbox"
                                id="fixplugorcase-1"
                                name="fixplugorcase"
                                value="1"
                                :checked="account.fixplugorcase == 1"
                              />
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                          </td>
                          <td>Fix Plug/Case</td>
                        </tr>
                        <tr>
                          <td>
                            <label
                              class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
                            >
                              <input
                                class="switch-input"
                                type="hidden"
                                name="changelabel"
                                value="0"
                              />
                              <input
                                class="switch-input"
                                type="checkbox"
                                id="changelabel-1"
                                name="changelabel"
                                value="1"
                                :checked="account.changelabel == 1"
                              />
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                          </td>
                          <td>Change Label</td>
                        </tr>
                        <tr>
                          <td>
                            <label
                              class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
                            >
                              <input
                                class="switch-input"
                                type="hidden"
                                name="removebracket"
                                value="0"
                              />
                              <input
                                class="switch-input"
                                type="checkbox"
                                id="removebracket-1"
                                name="removebracket"
                                value="1"
                                :checked="account.removebracket == 1"
                              />
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                          </td>
                          <td>Remove Bracket</td>
                        </tr>
                        <tr>
                          <td>
                            <label
                              class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
                            >
                              <input class="switch-input" type="hidden" name="wrongpart" value="0" />
                              <input
                                class="switch-input"
                                type="checkbox"
                                id="wrongpart-1"
                                name="wrongpart"
                                value="1"
                                :checked="account.wrongpart == 1"
                              />
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                          </td>
                          <td>Wrong Part</td>
                        </tr>
                        <tr>
                          <td>
                            <label
                              class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
                            >
                              <input class="switch-input" type="hidden" name="prog_mods" value="0" />
                              <input
                                class="switch-input"
                                type="checkbox"
                                id="progmods-1"
                                name="prog_mods"
                                value="1"
                                :checked="account.prog_mods == 1"
                              />
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                          </td>
                          <td>Prog Mods</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../api';

export default {
  name: 'OrderInformation',
  props: {
    accountData: String,
    userRole: String,
    customerData: String,
    accountUsers: String,
    stockInformation: String,
    roleName: String,
    orderStatuses: String,
    userName: String,
    isEdit: Boolean,
  },
  data() {
    return {
      account: this.accountData ? JSON.parse(this.accountData) : {},
      customermetadata: this.customerData ? JSON.parse(this.customerData) : {},
      users: this.accountUsers ? JSON.parse(this.accountUsers) : [],
      customerTrackingNumber: this.trackingNumber ? this.trackingNumber : '',
      account_id: '',
      onboard_testing: '',
      order_statuses: this.orderStatuses ? JSON.parse(this.orderStatuses) : [],
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
