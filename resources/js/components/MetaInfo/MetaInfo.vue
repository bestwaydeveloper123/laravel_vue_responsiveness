<template>
  <div class="card pb-1 mb-0">
    <div class="card-header card-header pt-1 pb-1 bg-gray-700 theme-color">
      <a
        class="text-light float-left"
        data-toggle="collapse"
        data-parent="#exampleAccordion"
        href="#exampleAccordion8"
        aria-expanded="true"
        aria-controls="exampleAccordion8"
      >
        <strong>
          <i class="fa fa-edit"></i> Meta Info
          <i class="fa fa-caret-down"></i>
        </strong>
      </a>
    </div>
    <div class="card-body pt-0 pb-0">
      <div id="exampleAccordion" data-children=".item">
        <div class="item">
          <div
            :class="{'show': (userRole != 3 || userRole != 4)}"
            class="collapse table-responsive"
            id="exampleAccordion8"
            role="tabpanel"
          >
            <table class="table table-sm table-responsive-sm table-bordered mb-0" width="50%">
              <tr>
                <td class="pt-0 pb-0" width="11%">
                  <label class="col-form-label col-form-label-sm" for="text-input4">Created By</label>
                </td>
                <td class="pt-0 pb-0" width="22%">
                  <input
                    class="form-control form-control-sm"
                    id="text-input4"
                    type="text"
                    name="text-input"
                    placeholder
                    readonly
                    v-model="account.created_by"
                  />
                </td>
                <td class="pt-0 pb-0" width="11%">
                  <label class="col-form-label col-form-label-sm" for="text-input5">Creation Time</label>
                </td>
                <td class="pt-0 pb-0" width="22%">
                  <input
                    class="form-control form-control-sm"
                    id="text-input5"
                    type="text"
                    name="text-input"
                    placeholder
                    readonly
                    v-model="account.created_at"
                  />
                </td>
                <td class="pt-0 pb-0" width="11%">
                  <label class="col-form-label col-form-label-sm" for="text-input6">Primary Points</label>
                </td>
                <td class="pt-0 pb-0" width="22%">
                  <input
                    class="form-control form-control-sm"
                    id="text-input6"
                    type="text"
                    name="text-input"
                    placeholder
                    v-model="primaryPoints"
                    readonly
                  />
                </td>
              </tr>
              <tr>
                <td class="pt-0 pb-0" style="width: 13%;">
                  <label class="col-form-label col-form-label-sm" for="text-input7">Last Modified By</label>
                </td>
                <td class="pt-0 pb-0">
                  <input
                    class="form-control form-control-sm"
                    id="text-input7"
                    type="text"
                    name="text-input"
                    placeholder
                    readonly
                    v-model="account.modified_by"
                  />
                </td>
                <td class="pt-0 pb-0" style="width: 13%;">
                  <label
                    class="col-form-label col-form-label-sm"
                    for="text-input8"
                  >Last Modified Time</label>
                </td>
                <td class="pt-0 pb-0">
                  <input
                    class="form-control form-control-sm"
                    id="text-input8"
                    type="text"
                    name="text-input"
                    placeholder
                    readonly
                    v-model="account.updated_at"
                  />
                </td>
                <td class="pt-0 pb-0" colspan="2">
                  <span v-if="isEdit" @click="showModal" class="btn btn-sm badge-success">
                    <i class="fa fa-history"></i> History
                  </span>
                  <history-modal
                    :class-name="getStyle"
                    v-if="has_modal"
                    @close="close"
                    :data="historyData"
                  />
                  <!-- <meta-info :account-id="{{ $account->id }}" is-edit /> -->
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import api from '../../api';
import HistoryModal from './HistoryModal';

export default {
  name: 'MetaInfo',

  components: {
    HistoryModal,
  },

  props: {
    userRole: String,
    isEdit: {
      type: Boolean,
      default: false,
    },
    accountData: String,
    primaryPoints: String,
  },

  data() {
    return {
      account: this.accountData ? JSON.parse(this.accountData) : {},
      has_modal: false,
      historyData: [],
    };
  },

  computed: {
    getStyle() {
      return (
        (this.headerColor || 'bg-gray-700 theme-color ') +
        (this.headerTextColor || 'text-light')
      );
    },
  },

  methods: {
    showModal() {
      window.axios
        .post('/api/SecondaryManagers', {
          account_id: this.account.id,
        })
        .then(res => {
          this.historyData = res.data;
          if (this.historyData.length > 0) {
            this.has_modal = true;
          }
        });
    },

    close() {
      this.has_modal = false;
    },
  },
};
</script>
