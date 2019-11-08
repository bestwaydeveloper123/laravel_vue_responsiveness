<template>
  <div>
    <attribute-card
      :account="account"
      :customer="customerData"
      :tracking="trackingData"
    />
    <card-collapse title="Customer Tracking" module="CustomerTracking" @clickbtn="create">
      <div class="row">
        <tracking-card
          v-for="(card, index) in data"
          :key="index"
          :data="card"
          :index="index"
          :header-style="getHeaderStyle"
          @edit="edit"
          @generate-label="showGenerateModal"
        />
      </div>
    </card-collapse>
    <input
      v-if="!account"
      type="hidden"
      name="customerTracking"
      :value="formDataForCreation"
    >
    <editor-modal
      v-if="hasModal"
      :tracking-data="handleTracking"
      :track="custmerTrack"
      :header-style="getHeaderStyle"
      @save="saveChange"
      @close="close"
    />
    <label-customer
      v-if="hasGenerateModal"
      :tracking="handleTracking"
      :account-id="account.id"
      :customer-data="customerData"
      :pricePaid="account.pricepaid"
      :header-style="getHeaderStyle"
      @close="closeGenerateModal"
      @update="updateTrackingByGeneration"
    />
    <loading :active.sync="isLoading" is-full-page></loading>
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import AttributeCard from '../AttributeCard/AttributeCard';
import CardCollapse from '../CardCollapse/CardCollapse';
import TrackingCard from './TrackingCard';
import EditorModal from './EditorModal';
import LabelCustomer from './LabelCustomer';
import api from '../../api';

export default {
  name: 'CustomerTracking',
  components: {
    Loading,
    CardCollapse,
    TrackingCard,
    EditorModal,
    LabelCustomer,
    AttributeCard,
  },
  props: {
    trackingData: {
      type: Array,
      default: null,
    },
    account: {
      type: Object,
      default: null,
    },
    customerData: {
      type: Object,
    },
    headerStyle: String,
  },
  data() {
    return {
      data: [],
      handleTracking: {
        index: -1,
        data: {},
      },
      custmerTrack: '',
      formDataForCreation: null,
      hasGenerateModal: false,

      isLoading: false,
      hasModal: false,
    };
  },
  created() {
    Object.assign(this.data, this.trackingData);
  },
  computed: {
    getHeaderStyle() {
      return this.headerStyle || 'bg-gray-700 theme-color text-light';
    },
  },
  methods: {
    create(val) {
      // val == 0 = +, 1 = OutBound, 2 = InBound
      this.custmerTrack = val;
      this.handleTracking.index = -1;
      this.handleTracking.data = {};
      this.hasModal = true;
    },
    edit(data, index) {
      this.handleTracking.index = index;
      this.handleTracking.data = data;
      this.hasModal = true;
    },
    saveChange(data) {
      if (this.handleTracking.index === -1 && this.account === null) {
        this.formDataForCreation = JSON.stringify(data);
        this.$nextTick(() => {
          $('#account-save').click();
        })
      } else {
        this.isLoading = true;
        api
          .updateOrCreateTracking(this.account.id, data)
          .then(res => {
            setTimeout(() => {
              if (this.handleTracking.index === -1) {
                this.data.push(res.data);
              } else {
                this.data[this.handleTracking.index] = res.data;
              }
              this.isLoading = false;
            }, 500);
          })
          .catch(err => {
            this.isLoading = false;
            alert(`Oops! Tracking ${this.handleVendor.index + 1} hasn't been saved.`);
          });
      }
      this.close();
    },
    close() {
      this.hasModal = false;
    },
    showGenerateModal(data, index){
      this.handleTracking.index = index;
      this.handleTracking.data = data;
      this.hasGenerateModal = true;
    },
    updateTrackingByGeneration(data) {
      this.data[this.handleTracking.index] = data;
      this.closeGenerateModal();
    },
    closeGenerateModal() {
      this.hasGenerateModal = false;
    },
  },
};
</script>
