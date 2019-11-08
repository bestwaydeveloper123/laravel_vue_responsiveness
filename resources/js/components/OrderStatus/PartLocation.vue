<template>
  <div class="width-100">
    <div v-if="!partLocation" class="float-left width-80">
      <span>
        <textarea
          id="orderstatuscustom"
          name="orderstatuscustom"
          rows="1"
          class="form-control form-control-sm"
        ></textarea>
        <!-- style="height:29px;overflow-y:hidden;" -->
      </span>
    </div>
    <div :class="floatRight()">
      <span
      v-if="partLocation"
      class="btn btn-sm badge-success m-1"
      data-toggle="modal"
      data-target="#orderstatus"
      @click="has_modal = true"
    >
      <i class="fa fa-plus"></i>
      </span>
      <span
        class="btn btn-sm badge-success"
        data-toggle="modal"
        data-target="#orderstatus"
        @click="has_history_modal = true"
      >
        <i class="fa fa-history"></i> History
      </span>
      <editor-modal
        :class-name="getStyle"
        v-if="has_modal"
        @save="saveChange"
        @close="has_modal = false"
        :role-name="roleName"
      />
      <history-modal
        :class-name="getStyle"
        v-if="has_history_modal"
        @close="has_history_modal = false"
        :account-team="accountTeam"
        :role-name="roleName"
        :account-id="accountId"
      />
    </div>
  </div>
</template>

<script>
import EditorModal from './EditorModal';
import HistoryModal from './HistoryModal';

export default {
  name: 'PartLocation',
  components: {
    EditorModal,
    HistoryModal,
  },
  props: {
    accountTeam: {
      type: String,
      default: '',
    },
    roleName: {
      type: String,
      default: '',
    },
    accountId: {
      type: Number,
      default: -1,
    },
    headerColor: {
      type: String,
      default: null,
    },
    headerTextColor: {
      type: String,
      default: null,
    },
    userName: {
      type: String,
      default: '',
    },
    partLocation: {
      type: String,
      default: '',
    }
  },
  data() {
    return {
      has_modal: false,
      has_history_modal: false,
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
    floatRight(){
      if(!this.partLocation) {
        return 'float-right';
      } else {
        return '';
      }
    },
    saveChange(data) {
      window.axios
        .post('/api/add-part-location', {
          id: this.accountId,
          data: {
            rolename: data.rolename,
            orderstatus: data.general,
            orderstatuscustom: data.custom,
            created_by: this.userName,
          },
        })
        .then(() => {
          // alert('Added Part Location Successfully!');
        })
        .catch(e => {
          alert(e);
        });

      this.has_modal = false;

      $('#account-save').click();
    },
  },
};
</script>
