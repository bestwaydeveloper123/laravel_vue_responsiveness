<template>
  <div class="card mb-1">
    <div class="card-header py-1" :class="getHeaderStyle">
      <strong> <i class="fa fa-edit"></i> Entries </strong>
      <span v-if="isEdit" class="btn btn-sm badge-success ml-5 py-0" @click="create">
        <i class="fa fa-plus"></i>
      </span>
      <span
        class="btn btn-sm badge-success ml-1 py-0"
        v-if="onBoardVal == 1 && role == 3 && isEdit"
        @click="showOnBoardModal"
      >
        OnBoard Testing
        <i class="fa fa-plus"></i>
      </span>
    </div>
    <editor-modal
      v-if="hasModal"
      :entry-data="handleEntry"
      @save="saveChange"
      @close="close"
    />
    <div class="card-body py-0">
      <entry-card
        v-for="(card, index) in data"
        :key="index"
        :pg-entries="pgEntries"
        :data="card"
        :index="index"
        :headerStyle="getHeaderStyle"
        @edit="edit"
      />
    </div>
    <OnBoardEditorModal
      v-if="has_onBoardModal"
      :onBoardValue="onBoardVal"
      @save="saveOnBoard"
      @close="closeOnBoard"
    />
    <loading :active.sync="isLoading" is-full-page></loading>
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import api from '../../api';
import EntryCard from './EntryCard.vue';
import EditorModal from './EditorModal.vue';
import OnBoardEditorModal from '../PartInformation/EditorModal';

export default {
  components: {
    Loading,
    EntryCard,
    EditorModal,
    OnBoardEditorModal,
  },
  props: {
    pgEntries: {
      type: Array,
      default: null,
    },
    role: String,
    accountId: String,
    onBoardValue: String,
    headerStyle: String,
    isEdit: Boolean,
  },

  data() {
    return {
      data: [],
      handleEntry: {
        index: -1,
        data: {},
      },
      entries: '',

      hasModal: false,
      has_onBoardModal: false,
      onBoardVal: false,
      pcmHWValue: '',
      unused: '',

      isLoading: false,
    };
  },
  created() {
    if (this.pgEntries !== null) {
      Object.assign(this.data, this.pgEntries);
    }
  },
  computed: {
    getHeaderStyle() {
      return this.headerStyle || 'bg-gray-700 theme-color text-light';
    },
  },
  mounted() {
    if (this.onBoardValue != '0') {
      this.onBoardVal = true;
    } else {
      this.onBoardVal = false;
    }
    this.$root.$on('pcmhw_value', function(pcm) {
      this.pcmHWValue = pcm;
    });
  },
  methods: {
    create() {
      this.handleEntry.index = -1;
      this.handleEntry.data = {};
      this.hasModal = true;
    },
    edit(data, index) {
      this.handleEntry.index = index;
      this.handleEntry.data = data;
      this.hasModal = true;
    },
    saveChange(data) {
      this.isLoading = true;
      if (this.accountId) {
        api
          .updateOrCreateEntry(this.accountId, data)
          .then(res => {
            setTimeout(() => {
              if (this.handleEntry.index === -1) {
                this.data.push(res.data);
              } else {
                this.data[this.handleEntry.index] = res.data;
              }
              this.isLoading = false;
            }, 500);
          })
          .catch(err => {
            console.log(err);
          });
      }
      this.close();

      this.$nextTick(() => {
        $('#account-save').click();
      });
    },
    close() {
      this.hasModal = false;
    },
    showOnBoardModal() {
      this.has_onBoardModal = true;
    },
    saveOnBoard(data) {
      const payload = {
        account_id: this.accountId,
        on_board_testing: data.onBoard,
      };
      api
        .ChangeOnBoardTesting(payload)
        .then(res => {
          if (res.data.IsSuccess) {
            this.onBoardVal = res.data.Data.on_board_testing;
            this.has_onBoardModal = false;
          }
        })
        .catch(err => console.log(err));
      this.has_onBoardModal = false;

      if (data.saveForFuture == true) {
        const pcmhwData = {
          pcmhw: this.$root.pcmHWValue,
        };
        api
          .savepcmhw(pcmhwData)
          .then(res => {
            if (res.data.IsSuccess) {
              this.hasModal = false;
            }
          })
          .catch(err => console.log(err));
      }
    },
    closeOnBoard() {
      this.has_onBoardModal = false;
    },

    saveCard(data, index) {
      this.data[index] = data;
      this.makeFormData();
    },

    makeFormData() {
      this.entries = JSON.stringify(this.data);
    },
  },

  beforeDestroy: function() {
    this.$root.$off('pcmhw_value');
  },
};
</script>
