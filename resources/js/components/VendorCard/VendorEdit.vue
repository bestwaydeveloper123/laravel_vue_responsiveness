<template>
  <div>
    <card-collapse
      title="Vendors"
      :role="role"
      addTitle="Junkyard"
      @clickbtn="create"
    >
      <div class="row">
        <vendor-card
          v-for="(card, index) in data"
          :key="index"
          :data="card"
          :creditCard="creditCard"
          :index="index"
          :header-style="getHeaderStyle"
          @generate-label="showGenerateModal"
          @edit="edit"
        />
      </div>
    </card-collapse>
    <input
      v-if="accountId === null"
      type="hidden"
      name="vendor"
      :value="formDataForCreation"
    >
    <editor-modal
      v-if="hasMmodal"
      :shopName="shopName"
      :data="handleVendor"
      :all-managers="users"
      :credit-card="creditCard"
      :header-style="getHeaderStyle"
      @save="saveChange"
      @close="close"
    />
    <generate-modal
      v-if="hasGenerateModal"
      :data="handleVendor"
      :account-id="accountId"
      :customer-data="customerData"
      :price-paid="pricePaid"
      :header-style="getHeaderStyle"
      @update="updateVendorByGeneration"
      @close="closeGenerateModel"
    />
    <loading :active.sync="isLoading" :is-full-page="true"/>
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import api from '../../api';
import CardCollapse from '../CardCollapse/CardCollapse.vue';
import VendorCard from './VendorCard';
import EditorModal from './EditorModal';
import GenerateModal from './GenerateModal';

export default {
  name: 'VendorEdit',
  components: {
    CardCollapse,
    VendorCard,
    EditorModal,
    GenerateModal,
    Loading,
  },
  props: {
    vendors: {
      type: String,
      default: null,
    },
    creditCard: {
      type: String,
      default: ''
    },
    role: {
      type: String,
      default: '',
    },
    users: {
      type: String,
      default: ''
    },
    accountId: {
      type: String,
      default: null,
    },
    pricePaid: String,
    customerData: {
      type: Object,
      default: null,
    },
    headerStyle: String,
  },
  data() {
    return {
      data: [],
      hasMmodal: false,
      hasGenerateModal: false,
      handleVendor: {
        index: -1,
        data: {},
      },
      vendorId: '',
      trackingId: '',
      junkyardAddress: {},
      formDataForCreation: null,

      isLoading: false,
      shopName: '',
    };
  },
  created() {
    if (this.vendors) {
      this.data = JSON.parse(this.vendors);
    }
  },
  computed: {
    getHeaderStyle() {
      return this.headerStyle || 'bg-gray-700 theme-color text-light';
    },
  },
  methods: {
    create() {
      this.handleVendor.index = -1;
      this.handleVendor.data = {};
      this.hasMmodal = true;
    },
    edit(data, index) {
      this.shopName = data.junkyardAddress?data.junkyardAddress.shopname:'';
      this.handleVendor.data = data;
      this.handleVendor.index = index;
      this.hasMmodal = true;
    },
    saveChange(data) {
      if (this.handleVendor.index === -1 && this.accountId === null) {
        this.formDataForCreation = JSON.stringify(data);
        this.$nextTick(() => {
          $('#account-save').click();
        });
      } else {
        this.isLoading = true;
        api
          .updateOrCreateVendor(this.accountId, data)
          .then(res => {
            setTimeout(() => {
              if (this.handleVendor.index === -1) {
                this.data.push(res.data);
              } else {
                this.data[this.handleVendor.index] = res.data;
              }
              this.isLoading = false;
            }, 500);
          })
          .catch(err => {
            this.isLoading = false;
            alert(`Oops! Vendor ${this.handleVendor.index} hasn's been saved.`);
          });
      }
      this.close();
    },
    close() {
      this.hasMmodal = false;
    },
    showGenerateModal(data, index) {
      this.handleVendor.data = data;
      this.handleVendor.index = index;
      this.hasGenerateModal = true;
    },
    updateVendorByGeneration(data) {
      this.data[this.handleVendor.index] = data;
      this.closeGenerateModel();
    },
    closeGenerateModel() {
      this.hasGenerateModal = false;
    },
  },
};
</script>
