<template>
  <div class="card mb-1">
    <div class="card-header py-1" :class="getHeaderStyle">
      <strong>
        <i class="fa fa-map-pin"></i> Customer Information
      </strong>
      <span class="btn btn-sm badge-success float-right ml-5 py-0" @click="editCustomer">
        <i class="fa fa-plus"></i>
      </span>
    </div>
    <div class="card-body py-0">
      <address class="mb-1">
        <div class="row border-bottom mb-1 pb-1">
          <div class="col-sm-1 border-right pl-1">
            <i class="fa fa-address-card"></i>
            <br>
            <span v-if="showLevel" class="badge badge-primary">{{ showLevel }}</span>
          </div>
          <div v-if="hasAddress" class="col-sm-10 pull-left mt-1">
            <span class="font-value">{{ data.shopname }}</span><br/>
            <span class="font-value">{{ data.shipto }}</span><br/>
            <span class="font-value">{{ data.street1 }}</span><br/>
            <span class="font-value">{{ data.street2 }}</span><br/>
            <span class="font-value">{{ getLocation }}</span><br/>
          </div>
        </div>
        <div class="row border-bottom mb-1 pb-1">
          <div class="col-sm-1 pull-left border-right pl-1">
            <i class="fa fa-phone"></i>
          </div>
          <div class="col-sm-10 pull-left">
            <span class="font-value">{{ displayPhoneNumber(data.phone) }}</span><br/>
            <span class="font-value">{{ displayPhoneNumber(data.phone2) }}</span><br/>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-1 pull-left border-right pl-1">
            <i class="fa fa-envelope"></i>
          </div>
          <div class="col-sm-10 pr-0">
            <div v-if="data.email" class="row pb-1">
              <div class="col-sm mr-auto">
                <span class="font-value" id="e1">{{ data.email }}</span>
              </div>
              <div class="btn-group" role="group" aria-label="mail-controll">
                <a :href="'mailto:' + data.email" class="btn btn-sm btn-success py-0">
                  <i class="fa fa-paper-plane"></i>
                </a>
                <button type="button" class="btn btn-sm btn-primary py-0" @click="copyemail('e1')">
                  <i class="fa fa-copy"></i>
                </button>
              </div>
            </div>
            <div v-if="data.email2" class="row pb-1">
              <div class="col-sm mr-auto">
                <span class="font-value" id="e2">{{ data.email2 }}</span>
              </div>
              <div class="btn-group" role="group" aria-label="mail-controll">
                <a :href="'mailto:' + data.email" class="btn btn-sm btn-success py-0">
                  <i class="fa fa-paper-plane"></i>
                </a>
                <button type="button" class="btn btn-sm btn-primary py-0" @click="copyemail('e2')">
                  <i class="fa fa-copy"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </address>
    </div>
    <editor-modal
      v-if="hasModal"
      :header-style="getHeaderStyle"
      :data="data"
      @save="saveChange"
      @close="close"
      :shop-name="data.shopname"
      :ship-to="data.shipto"
    />
    <input
      v-if="accountId == ''"
      type="hidden"
      name="customerMeta"
      :value="getFormData"
    >
    <loading :active.sync="isLoading" :is-full-page="true"></loading>
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import EditorModal from './EditorModal';
import api from '../../api';

export default {
  name: 'AddressEditCard',
  components: {
    Loading,
    EditorModal,
  },
  props: {
    customerData: {
      type: Object,
      default: null,
    },
    accountId: {
      type: String,
      default: null,
    },
    formControl: Boolean,
    headerStyle: String,
  },
  data() {
    return {
      data: {
        shopname: '',
        shipto: '',
        street1: '',
        street2: '',
        city: '',
        state: '',
        zip: '',
        country: '',
        phone: '',
        phone2: '',
        email: '',
        email2: '',
        level: '',
      },
      isLoading: false,
      hasModal: false,
    };
  },
  created() {
    Object.assign(this.data, this.customerData);
  },
  computed: {
    hasAddress() {
      if (this.data.shopname
        || this.data.shipto
        || this.data.street1
        || this.data.street2
        || this.data.city
        || this.data.state
        || this.data.zip
        || this.data.country)
      {
        return true;
      }
      return false;
    },
    getLocation() {
      const { city, state, zip, country } = this.data;
      let location = city ? `${city}, ` : '';
      location += state ? `${state}, ` : '';
      location += zip ? `${zip}, ` : '';
      location += country ? country : '';
      return location;
    },
    showLevel() {
      if (this.data.level === 'Plus (2+ sales)') {
        return 'P1';
      } else if (this.data.level === 'Premier (3+ sales)') {
        return 'P2';
      } else if (this.data.level === 'Platinum (5+ sales)') {
        return 'P3';
      }
      return null;
    },
    getHeaderStyle() {
      return this.headerStyle || 'bg-gray-700 theme-color text-light';
    },
    mailToCopy() {
      if (this.data.email && this.data.email2) {
        return this.data.email + ';' + this.data.email2;
      } else if (this.data.email === '' && this.data.email2) {
        return this.data.email2;
      } else if (this.data.email && this.data.email2 === '') {
        return this.data.email;
      } else if ((this.data.email === '') & (this.data.email2 === '')) {
        return '';
      }
    },
    getFormData() {
      return JSON.stringify(this.data);
    }
  },
  methods: {
    editCustomer() {
      this.hasModal = true;
    },
    saveChange(data) {
      Object.assign(this.data, data);
      this.data.phone = data.phone && this.filterPhoneNumber(data.phone);
      this.data.phone2 = data.phone2 && this.filterPhoneNumber(data.phone2);

      this.hasModal = false;

      // accident code
      this.sync_level();
      if (this.accountId) {
        this.isLoading = true;
        api
          .updateCustomer(this.accountId, this.data)
          .then(res => {
            setTimeout(() => {
              this.data = res.data;
              this.isLoading = false;
            }, 500);
          })
          .catch(err => {
            this.isLoading = false;
            alert(`Oops! Customer meta data hasn't been saved.`);
          });
      } else {
        this.$nextTick(() => {
          $('#account-save').click();
        });
      }
    },
    close() {
      this.hasModal = false;
    },
    copyemail(containerid) {
      var range = document.createRange();
      range.selectNode(document.getElementById(containerid));
      window.getSelection().removeAllRanges(); // clear current selection
      window.getSelection().addRange(range); // to select text
      document.execCommand('copy');
      window.getSelection().removeAllRanges(); // to deselect
    },
    filterPhoneNumber(phone) {
      let filtered = phone;
      filtered = filtered.replace(/\(|\)|\-|\s/g, '');
      return filtered;
    },
    displayPhoneNumber(phone) {
      if (typeof phone === 'string' && phone.length === 10) {
        let d = this.splice(phone, 6, 0, ' - ');
        d = this.splice(d, 3, 0, ') ');
        d = this.splice(d, 0, 0, '(');

        return d;
      } else {
        return phone;
      }
      return '';
    },
    splice(obj, idx, rem, str) {
      return obj.slice(0, idx) + str + obj.slice(idx + Math.abs(rem));
    },
    sync_level() {
      const e = document.getElementById('customer-level-1');
      e.value = this.data.level;
      e.dispatchEvent(new Event('change'));
    },
  },
};
</script>

<style scoped>
.font-key {
  font-size: 0.8rem;
}
.font-value {
  font-size: 1rem;
  font-weight: bold;
}
</style>
