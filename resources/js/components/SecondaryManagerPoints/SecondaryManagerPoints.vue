<template>
  <div>
    <span v-if="isPoints" @click="addPoints" class="btn btn-sm badge-success">
      <i class="fa fa-sort-numeric-asc"></i> 100Pts
    </span>
    <button v-else class="btn btn-sm btn-secondary" disabled>
      <i class="fa fa-sort-numeric-asc"></i> 100Pts
    </button>
    <span v-if="isEdit" @click="showModal" class="btn btn-sm badge-success">
      <i class="fa fa-history"></i> History
    </span>
    <history-modal :class-name="getStyle" v-if="has_modal" @close="close" :data="historyData" />
  </div>
</template>


<script>
import api from '../../api';
import HistoryModal from './HistoryModal';

export default {
  name: 'SecondaryManagerPoints',

  components: {
    HistoryModal,
  },

  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
    accountId: {
      type: Number,
      default: -1,
    },
    isPoints: {
      type: String,
    },
  },

  data() {
    return {
      has_modal: false,
      historyData: [],
      pointButtonClick: false,
    };
  },

  mounted() {
    if (this.isPoints == '1') {
      this.pointButtonClick = true;
    } else {
      this.pointButtonClick = false;
    }
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
    addPoints() {
      let obj = {
        account_id: this.accountId,
      };
      api
        .SecondaryManagerPoint(obj)
        .then(res => {
          if (res.data.success) {
            this.$toast.open({
              message: res.data.message,
              position: 'top',
            });
          } else {
            this.$toast.open({
              message: res.data.message,
              position: 'top',
              type: 'error',
            });
          }
        })
        .catch(err =>
          this.$toast.open({
            message: 'Something went wrong!',
            position: 'top',
            type: 'error',
          }),
        );
    },
    showModal() {
      let obj = {
        account_id: this.accountId
      }
      api.SecondaryManagerPointHistory(obj).then(res => {
        if (res.data.success) { 
          this.historyData = res.data.data
          this.has_modal = true
        }
      });
    },

    close() {
      this.has_modal = false;
    },
  },
};
</script>
