<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-body p-0 mb-1 overflow-auto">
            <table class="table table-striped table-responsive-sm table-sm mb-0">
              <tr>
                <td class="py-0" width="10%">
                  <label class="col-form-label col-form-label-sm" for="entry-type">Entry Type</label>
                </td>
                <td class="py-0" width="40%">
                  <select class="form-control form-control-sm" type="text" v-model="data.entrytype">
                    <option value="Programmed">Programmed</option>
                    <option value="P/N and Sticker Check">P/N and Sticker Check</option>
                    <option value="Comm Check">Comm Check</option>
                    <!-- <option value="Wrong Hardware">Wrong Hardware</option>
                    <option value="Wrong Part">Wrong Part</option>
                    <option value="Security Mismatch">Security Mismatch</option> -->
                    <option value="Issues">Issues</option>
                    <option value="Troubleshooting">Troubleshooting</option>
                    <option value="Testing">Testing</option>
                    <!-- <option value="Bad Unit/DOA">Bad Unit/DOA</option>
                    <option value="More Info Needed">More Info Needed</option> -->
                    <option value="More Info Needed">More Info Needed</option>
                    <option value="Possible Duplicate">Possible Duplicate</option>
                    <option value="RMA Test">RMA Test</option>
                    <option value="RMA Comm Test">RMA Comm Test</option>
                    <option value="Onboard Service">Onboard Service</option>
                  </select>
                </td>
                <td class="py-0" width="10%">
                  <label class="col-form-label col-form-label-sm" for="frd-keys">FRD Keys</label>
                </td>
                <td class="py-0" width="40%">
                  <div class="btn-group btn-group-sm col-form-label col-form-label-sm selectbackground" role="group">
                    <input id="frdkeys1" type="radio" class="btn btn-secondary py-0" value="Keys Wiped & Included" v-model="data.frdkeys">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="frdkeys1">Wiped & Included</label>
                    <input id="frdkeys2" type="radio" class="btn btn-secondary py-0" value="Keys Programmed Off-Board" v-model="data.frdkeys">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="frdkeys2">OffBoard</label>
                    <input id="frdkeys3" type="radio" class="btn btn-secondary py-0" value="Keys Programmed On-Board" v-model="data.frdkeys">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="frdkeys3">OnBoard</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="py-0">
                  <label class="col-form-label col-form-label-sm" for="stock-number">Stock#</label>
                </td>
                <td class="py-0">
                  <input id="stock-number" class="form-control form-control-sm" type="text" v-model="data.salespersonstockno">
                </td>
                <td class="py-0">
                  <label class="col-form-label col-form-label-sm">CHY Skim Reset</label>
                </td>
                <td class="py-0">
                  <div class="btn-group btn-group-sm col-form-label col-form-label-sm selectbackground" role="group">
                    <input id="chyskimreset1" type="radio" class="btn btn-secondary py-0" value="Skim Was Reset" v-model="data.chyskimreset">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chyskimreset1">Was Reset</label>
                    <input id="chyskimreset2" type="radio" class="btn btn-secondary py-0" value="Skim Not Reset" v-model="data.chyskimreset">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chyskimreset2" >Not Reset</label>
                    <input id="chyskimreset3" type="radio" class="btn btn-secondary py-0" value="Skim Reset Not Supported" v-model="data.chyskimreset">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chyskimreset3">Not Supported</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="py-0" rowspan="3">
                  <label class="col-form-label col-form-label-sm" for="pro-notes">Notes</label>
                </td>
                <td class="py-0 word-break" rowspan="3">
                  <trix-pro-editor
                    id="pro-notes"
                    @updateContent="updateNotes"
                    :value="data.programmingnotes"
                  ></trix-pro-editor>
                </td>
                <td class="py-0">
                  <label class="col-form-label col-form-label-sm" for="chydonorinfo">CHY Donor Info</label>
                </td>
                <td class="py-0">
                  <div class="btn-group btn-group-sm col-form-label col-form-label-sm selectbackground" role="group">
                    <input id="chydonorinfo1" type="radio" class="btn btn-secondary py-0" value="Donor has skim(GXX/GXW)" v-model="data.chydonorinfo">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chydonorinfo1">GXX/GXW</label>
                    <input id="chydonorinfo2" type="radio" class="btn btn-secondary py-0" value="Donor does not have skim(GXX/GXW)" v-model="data.chydonorinfo">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chydonorinfo2">NoSkim</label>
                  </div>
                  <div class="btn-group btn-group-sm col-form-label col-form-label-sm selectbackground ml-2" role="group">
                    <input id="chydonorinfo21" type="radio" class="btn btn-secondary py-0" value="Donor has LSA" v-model="data.chydonorinfo2">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chydonorinfo21">LSA</label>
                    <input id="chydonorinfo22" type="radio" class="btn btn-secondary py-0" value="Donor does not have LSA" v-model="data.chydonorinfo2">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chydonorinfo22">NoLSA</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="py-0">
                  <label class="col-form-label col-form-label-sm" for="chycustinfo">CHY Cust Info</label>
                </td>
                <td class="py-0">
                  <div class="btn-group btn-group-sm col-form-label col-form-label-sm selectbackground" role="group">
                    <input id="chycustinfo1" type="radio" class="btn btn-secondary py-0" value="Customer has skim(GXX/GXW)" v-model="data.chycustinfo">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chycustinfo1">GXX/GXW</label>
                    <input id="chycustinfo2" type="radio" class="btn btn-secondary py-0" value="Customer does not have skim(GXX/GXW)" v-model="data.chycustinfo">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chycustinfo2">NoSkim</label>
                  </div>
                  <div class="btn-group btn-group-sm col-form-label col-form-label-sm selectbackground ml-2" role="group">
                    <input id="chycustinfo21" type="radio" class="btn btn-secondary py-0" value="Customer has skim LSA" v-model="data.chycustinfo2">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chycustinfo21">LSA</label>
                    <input id="chycustinfo22" type="radio" class="btn btn-secondary py-0" value="Customer does not have LSA" v-model="data.chycustinfo2">
                    <label class="group-btn btn btn-secondary py-0 mb-0" for="chycustinfo22">NoLSA</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="py-0">
                  <label class="col-form-label col-form-label-sm" for="text-input">General</label>
                </td>
                <td class="py-0">
                  <div class="btn-group btn-group-sm col-form-label col-form-label-sm selectbackground" role="group">
                    <input id="general1" type="checkbox" class="btn btn-secondary py-0" v-model="data.general">
                    <label class="btn btn-secondary py-0 mb-0" for="general1">Programmed No VIN</label>
                  </div>
                </td>
              </tr>
              <tr v-if="data.entrytype === 'P/N and Sticker Check'">
                <td class="py-0 align-middle">
                  <label class="col-form-label col-form-label-sm">P/N and Sticker Check</label>
                </td>
                <td>
                  <div class="custom-control custom-switch">
                    <input id="need-onboard-testing" type="checkbox" class="custom-control-input" v-model="data.needonboardtesting">
                    <label for="need-onboard-testing" class="custom-control-label">Needs Onboard Testing</label>
                  </div>
                </td>
              </tr>
              <tr v-if="data.entrytype === 'Issues'">
                <td class="py-0 align-middle" width="10%">
                  <label class="col-form-label col-form-label-sm" >Issues</label>
                </td>
                <td>
                  <div class="custom-control custom-switch">
                    <input id="doa" type="checkbox" class="custom-control-input" v-model="data.doa">
                    <label class="custom-control-label" for="doa">DOA</label>
                  </div>
                  <div class="custom-control custom-switch">
                    <input id="wrong-hw" type="checkbox" class="custom-control-input" v-model="data.wronghw">
                    <label class="custom-control-label" for="wrong-hw">Correct ECU style, Wrong H/W</label>
                  </div>
                  <div v-if="data.wronghw" class="mt-1">
                    <label class for="correct-hw-needed">Correct H/W Needed</label>
                    <input type="text" class="form-control" v-model="data.correcthwneeded">
                  </div>
                  <div class="custom-control custom-switch">
                    <input id="wrong-ecu-hw" type="checkbox" class="custom-control-input" v-model="data.wrongecuhw">
                    <label class="custom-control-label" for="wrong-ecu-hw">Wrong ECU style, Wrong H/W</label>
                  </div>
                  <div class="custom-control custom-switch">
                    <input id="wrong-part-type" type="checkbox" class="custom-control-input" v-model="data.wrongparttype">
                    <label class="custom-control-label" for="wrong-part-type">Wrong Part Type</label>
                  </div>
                  <div class="custom-control custom-switch">
                    <input id="purchase-info-mismatch" type="checkbox" class="custom-control-input" v-model="data.purchaseinfomismatch">
                    <label class="custom-control-label" for="purchase-info-mismatch">Purchase Info Mismatch</label>
                  </div>
                  <div class="custom-control custom-switch">
                    <input id="security-mismatch" type="checkbox" class="custom-control-input" v-model="data.securitymismatch">
                    <label class="custom-control-label" for="security-mismatch">Security Mismatch</label>
                  </div>
                  <div class="custom-control custom-switch">
                    <input id="badly-damaged-unit" type="checkbox" class="custom-control-input" v-model="data.badlydamagedunit">
                    <label class="custom-control-label" for="badly-damaged-unit">Badly Damaged Unit</label>
                  </div>
                </td>
              </tr>
              <tr v-if="data.entrytype === 'More Info Needed'">
                <td class="py-0 align-middle" width="10%">
                  <label class="col-form-label col-form-label-sm">More Info Needed</label>
                </td>
                <td>
                  <div class="custom-control custom-switch">
                    <input id="need-sw" type="checkbox" class="custom-control-input" v-model="data.needswpn">
                    <label class="custom-control-label" for="need-sw">Need Software P/N from Cust</label>
                  </div>
                  <div class="custom-control custom-switch">
                    <input id="need-cust-vin" type="checkbox" class="custom-control-input" v-model="data.needcustvin">
                    <label class="custom-control-label" for="need-cust-vin">Need Cust VIN</label>
                  </div>
                </td>
              </tr>
            </table>
          </div>

          <div v-if="error" class="alert alert-warning alert-dismissible fade show" role="alert">
            <div v-html="error"></div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-footer py-0 mt-1 bg-gray-300 theme-color">
            <button class="btn btn-secondary" type="button" @click="close">Close</button>
            <button class="btn btn-success" type="button" @click="saveChange">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import TrixProEditor from '../TrixProEditor/TrixProEditor';

export default {
  components: {
    TrixProEditor,
  },
  props: {
    entryData: {
      index: {
        type: Number,
        default: -1
      },
      data: {
        type: Object,
        default: {},
      },
    },
  },
  data() {
    return {
      data: {
        id: -1,
        entrytype: '',
        salespersonstockno: '',
        frdkeys: '',
        chyskimreset: '',
        chydonorinfo: '',
        chydonorinfo2: '',
        chycustinfo: '',
        chycustinfo2: '',
        general: false,
        programmingnotes: '',
        doa: false,
        wronghw: false,
        wrongecuhw: false,
        wrongparttype: false,
        purchaseinfomismatch: false,
        securitymismatch: false,
        badlydamagedunit: false,
        needsw: false,
        needswpn: false,
        needcustvin: false,
        vendorsentincorrect: false,
        description: '',
        correcthwneeded: '',
        needonboardtesting: false,
      },
      error: '',
    };
  },
  created() {
    const { index, data } = this.entryData;
    if (index !== -1) {
      Object.assign(this.data, data);
    }
  },
  mounted() {
    var vm = this;
    $('label.group-btn').mousedown(function(e) {
      var $self = $(this);
      var radioId = $self.attr('for');
      var $radio = $('#' + radioId);
      var selname = radioId.substr(0, radioId.length - 1);
      if ($radio.is(':checked')) {
        var uncheck = function() {
          setTimeout(function() {
            $radio.removeAttr('checked');
            vm.data[selname] = null;
          }, 0);
        };
        var unbind = function() {
          $self.unbind('mouseup', up);
        };
        var up = function() {
          uncheck();
          unbind();
        };
        $self.bind('mouseup', up);
        $self.one('mouseout', unbind);
      }
    });
  },
  methods: {
    updateNotes(notes) {
      this.data.programmingnotes = notes;
    },
    close() {
      this.$emit('close');
    },
    saveChange() {
      if (this.data.entrytype) {
        this.$emit('save', this.data);
      } else {
        this.error = 'Please select the <strong>Entry Type</strong>.';
      }
    },
  },
};
</script>
