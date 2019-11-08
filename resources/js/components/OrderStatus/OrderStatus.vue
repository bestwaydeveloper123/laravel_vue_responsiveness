<template>
  <div>
    <div class="card mb-1">
      <div class="card-header pt-1 pb-1" :class="getStyle">
        <strong> <i class="fa fa-home"></i> Order Status </strong>
        <span
          class="btn btn-sm badge-success ml-5 py-0"
          @click="showModal"
        >
          <i class="fa fa-plus"></i>
        </span>
        <span
          v-if="isEdit"
          class="btn btn-sm badge-success py-0"
          @click="showHistoryModal"
        >
          <i class="fa fa-history"></i> History
        </span>
      </div>
      <div class="card-body p-1 overflow-auto">
        <table class="table table-sm table-responsive p-0 mb-1" width="100%">
          <tr v-for="(item, index) in data_.slice(0, 5)" :key="index" class="">
            <td class="py-1 put-center" width="15%">
              <button
                type="button"
                class="btn btn-pill btn-sm pb-0 pt-0 theme-color"
                :class="teamButton"
              >
                {{ roleName }}
              </button>
            </td>
            <td class="py-1 put-center" width="15%">
              {{ item.created_by }}
            </td>
            <td class="py-1" width="55%">
              <div v-html="showItem(item)"/>
            </td>
            <td v-if="item.created_at" class="py-1" width="15%">
              {{ item.created_at }}
            </td>
            <td v-else class="py-1" width="15%">
              {{ todayDate() }}
            </td>
          </tr>
        </table>
      </div>

      <input type="hidden" name="orderstatus" :value="formData" />
    </div>
    <!--============================================ MODALS ===========================================-->
    <editor-modal
      :class-name="getStyle"
      v-if="has_modal"
      :roleName="roleName"
      @save="saveChange"
      @close="close"
    />
    <history-modal
      :class-name="getStyle"
      v-if="has_history_modal"
      @close="closeHistoryModal"
      :account-team="accountTeam"
      :role-name="roleName"
      :account-id="accountId"
    />
    <!--============================================ MODALS END===========================================-->
  </div>
</template>

<script>
import HistoryModal from './HistoryModal';
import EditorModal from './EditorModal';

const TEAM = ['Green Team', 'Pink Team', 'Purple Team', 'Gold Team'];

export default {
  name: 'OrderStatus',

  components: {
    EditorModal,
    HistoryModal,
  },

  props: {
    roleName: {
      type: String,
    },
    data: {
      type: Array,
      default: undefined,
    },
    headerColor: {
      type: String,
      default: null,
    },
    headerTextColor: {
      type: String,
      default: null,
    },
    isEdit: {
      type: Boolean,
      default: false,
    },
    accountId: {
      type: Number,
      default: -1,
    },
    accountTeam: {
      type: String,
      default: null,
    },
    userName: {
      type: String,
      default: '',
    }
  },
  data() {
    return {
      data_: this.data || [],
      newData: [],

      has_modal: false,
      has_history_modal: false,
    };
  },
  computed: {
    formData() {
      return JSON.stringify(this.newData);
    },
    teamButton() {
      if (this.roleName.toLowerCase() == 'admin') {
        return 'bg-orange white-font';
      } else if (this.roleName.toLowerCase() == 'shipping') {
        return 'bg-gray-900 white-font';
      } else if (this.roleName.toLowerCase() == 'programming') {
        return 'bg-gray white-font';
      } else if (this.accountTeam == 'Yellow Team') {
        return 'bg-yellow white-font';
      } else if (this.accountTeam == 'Green Team') {
        return 'bg-green white-font';
      } else if (this.accountTeam == 'Gold Team') {
        return 'bg-gold white-font';
      } else if (this.accountTeam == 'Purple Team') {
        return 'bg-purple white-font';
      } else if (this.accountTeam == 'Pink Team') {
        return 'bg-pink white-font';
      }
    },
    getStyle() {
      return (
        (this.headerColor || 'bg-gray-700 theme-color ') +
        (this.headerTextColor || 'text-light')
      );
    },
  },
  methods: {
    todayDate(){
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1; //January is 0!

      var yyyy = today.getFullYear();
      if (dd < 10) {
        dd = '0' + dd;
      }
      if (mm < 10) {
        mm = '0' + mm;
      }
      var today = yyyy + '-' + mm + '-' + dd + ' ' + today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
      return today;
    },
    showModal() {
      this.has_modal = true;
    },
    saveChange(data) {
      const status = {
        orderstatus: data.general,
        orderstatuscustom: data.custom,
        rolename: data.rolename,
        created_by: this.userName,
        team: this.accountTeam,
      };

      this.data_.unshift(status);
      this.newData.push(status);

      this.has_modal = false;

      this.$nextTick(() => {
        $('#account-save').click();
      })
    },
    close() {
      this.has_modal = false;
    },
    closeHistoryModal() {
      this.has_history_modal = false;
    },
    showItem(item) {
      return (
        (item.orderstatus ? item.orderstatus + '<br>' : '') +
        (item.orderstatuscustom ? item.orderstatuscustom : '')
      );
    },
    showHistoryModal() {
      this.has_history_modal = true;
    },
  },
};
</script>
<style scoped>
.white-font {
  color: #ffffff !important;
}
</style>
