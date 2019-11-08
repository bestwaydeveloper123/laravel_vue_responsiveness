<template>
  <div class="card mb-1">
    <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
      <strong>
        <i class="fa fa-microchip"></i> Part Information
      </strong>
      <span
        v-if="role == 3"
        data-toggle="modal"
        class="btn btn-sm badge-success float-right py-0"
        @click="showModal"
      >
        <i class="fa fa-plus"></i>
      </span>
    </div>
    <div class="card-body pt-0 pb-0 pr-0 pl-0">
      <table
        id="account_table"
        class="table table-responsive-sm table-sm pull-left mb-0"
        width="2/3"
      >
        <tr>
          <td class="pt-0 pb-0" width="20.33%">
            <label class="col-form-label col-form-label-sm" for="text-input">Item Purchased</label>
          </td>
          <td class="pt-0 pb-0">
            <auto-complete @spread="spread" :value="item"></auto-complete>
          </td>
        </tr>
        <tr>
          <td class="pt-0 pb-0">
            <label
              class="col-form-label col-form-label-sm"
              for="ispartinformation"
            >Save For Autocomplete</label>
          </td>
          <td class="pt-0 pb-0">
            <div class="custom-control custom-checkbox">
              <input
                type="checkbox"
                name="ispartinformation"
                id="ispartinformation"
                class="custom-control-input"
              />
              <label for="ispartinformation" class="custom-control-label"></label>
            </div>
          </td>
        </tr>
        <tr>
          <td class="pt-0 pb-0">
            <label class="col-form-label col-form-label-sm" for="text-input">PCM H/W</label>
          </td>
          <td class="pt-0 pb-0">
            <input
              v-model="pcmHW"
              class="form-control form-control-sm"
              id="pcmhardwaretype"
              type="text"
              name="pcmhardwaretype"
              @keyup="pcmHwValueChange"
            />
            <!-- :value="pcmHW" -->
          </td>
        </tr>
        <tr>
          <td class="pt-0 pb-0">
            <label class="col-form-label col-form-label-sm" for="text-input">Computer Type</label>
          </td>
          <td class="pt-0 pb-0">
            <!-- <input
              class="form-control form-control-sm"
              id="computertype"
              type="text"
              name="computertype"
              :value="computerType"
            >-->
            <select
              v-model="computerType"
              class="form-control-sm form-control"
              name="computertype"
              id="computertype"
            >
              <!-- <option value="'.$row["computertype"].'">'.$row["computertype"].'</option> -->
              <option
                value="Single Plug Ford(DPC-422,MPC-161,MTC-101"
              >Single Plug Ford(DPC-422,MPC-161,MTC-101)</option>
              <option value="NGC1,2,3,4(04896450AE)">NGC1,2,3,4(04896450AE)</option>
              <option value="GPEC Chrysler(05150816AB)">GPEC Chrysler(05150816AB)</option>
              <option
                value="SBEC III(2 Plug Chrysler HW Ex.05017588AA)"
              >SBEC III(2 Plug Chrysler HW Ex.05017588AA)</option>
              <option value="JTEC (Any Type Ex. 05014152AA)">JTEC (Any Type Ex. 05014152AA)</option>
              <option
                value="3 Plug Ford (Either two 50pin plug and one 70pin or 3 50pin Normal sticker away from plugs- LBO,VH3-f9632)"
              >3 Plug Ford (Either two 50pin plug and one 70pin or 3 50pin Normal sticker away from plugs- LBO,VH3-f9632)</option>
              <option
                value="Mazda (Not Tribute, or B2500/B3000/B4000)"
              >Mazda (Not Tribute, or B2500/B3000/B4000)</option>
              <option value="Other">Other</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="pt-0 pb-0">
            <label class="col-form-label col-form-label-sm" for="vin">VIN</label>
          </td>
          <td class="pt-0 pb-0">
            <textarea
              class="form-control form-control-sm"
              id="vin"
              name="vin"
              rows="1"
              v-model="vin"
              @keyup="vinValueChange"
            ></textarea>
          </td>
        </tr>
        <tr>
          <td class="pt-0 pb-0">
            <label class="col-form-label col-form-label-sm" for="programmingdetails">Prog Details</label>
          </td>
          <td class="pt-0 pb-0">
            <textarea
              class="form-control form-control-sm"
              id="programmingdetails"
              name="programmingdetails"
              rows="1"
              v-model="proDetails"
              @keyup="pDetailsValueChange"
            ></textarea>
          </td>
        </tr>
      </table>
    </div>
    <editor-modal v-if="has_modal" :onBoardValue="onBoardVal" @save="saveChange" @close="close" />
  </div>
</template>

<script>
import api from '../../api';
import AutoComplete from './AutoComplete';
import EditorModal from './EditorModal';
import { serverBus } from '../../app';
export default {
  name: 'PartInformation',

  components: {
    AutoComplete,
    EditorModal,
  },

  props: {
    item: String,
    pcm: String,
    com: String,
    details: String,
    price: String,
    role: String,
    accountId: String,
    onBoardValue: String,
    customerVin: String,
  },
  data() {
    return {
      pcmHW: this.pcm || '',
      computerType: this.com || '',
      // pricePaid: this.price || '',
      proDetails: this.details || '',
      has_modal: false,
      onBoardVal: false,
      vin: this.customerVin,
    };
  },
  mounted() {
    if (this.onBoardValue != '0') {
      this.onBoardVal = true;
    } else {
      this.onBoardVal = false;
    }
    this.$nextTick(function() {
      localStorage.setItem('accountId', this.accountId);
    });
  },
  watch: {
    pcmHW: function(val, oldVal) {
      this.checkPcmHw(val);
      this.$root.$emit('pcmhw_value', val);
    },
  },
  methods: {
    vinValueChange() {
      document.getElementById('customervin2').value = document.getElementById(
        'vin',
      ).value;
      document.getElementById('customervin').value = document.getElementById(
        'vin',
      ).value;
      document.getElementById('customervin1').value = document.getElementById(
        'vin',
      ).value;
    },
    pDetailsValueChange() {
      if (document.getElementById('prog-notes')) {
        document.getElementById('prog-notes').value = document.getElementById(
          'programmingdetails',
        ).value;
      }
      if (document.getElementById('prog-notes-2')) {
        document.getElementById('prog-notes-2').value = document.getElementById(
          'programmingdetails',
        ).value;
      }
    },
    pcmHwValueChange(){
      if (document.getElementById('hardware')) {
        document.getElementById('hardware').value = document.getElementById(
          'pcmhardwaretype',
        ).value;
      }
      if (document.getElementById('hardware2')) {
        document.getElementById('hardware2').value = document.getElementById(
          'pcmhardwaretype',
        ).value;
      }
    },
    checkPcmHw(val) {
      const pcmHwValue = {
        pcmhw: val,
      };
      api
        .checkpcmhw(pcmHwValue)
        .then(res => {
          if (res.data.IsSuccess) {
            this.has_modal = true;
          }
        })
        .catch(err => console.log(err));
    },
    spread(data) {
      this.pcmHW = data.pcm_hw;
      this.computerType = data.computer_type;
      // this.pricePaid = data.price_paid;
    },
    showModal() {
      this.has_modal = true;
    },
    saveChange(data) {
      const payload = {
        account_id: this.accountId,
        on_board_testing: data.onBoard,
      };
      api
        .ChangeOnBoardTesting(payload)
        .then(res => {
          if (res.data.IsSuccess) {
            this.onBoardVal = res.data.Data.on_board_testing;
            this.has_modal = false;
          }
        })
        .catch(err => console.log(err));

      if (data.saveForFuture == true) {
        const pcmhwData = {
          pcmhw: this.pcmHW,
        };
        api
          .savepcmhw(pcmhwData)
          .then(res => {
            if (res.data.IsSuccess) {
              this.has_modal = false;
            }
          })
          .catch(err => console.log(err));
      }
      this.has_modal = false;
    },
    close() {
      this.has_modal = false;
    },
  },
};
</script>
