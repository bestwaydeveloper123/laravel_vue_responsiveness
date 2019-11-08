<template>
  <div class="col-sm-12 col-md-12">
    <div class="card mb-0">
      <div class="card-header py-1" :class="headerStyle">
        Entry {{ pgEntries.length - index }}
        <span class="badge badge-pill badge-danger float-right" @click="edit">edit</span>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-striped table-responsive-sm mb-0">
          <tr>
            <td class="py-0" colspan="1">
              <label class="col-form-label col-form-label-sm text-success">{{ data.entrytype }}</label>
            </td>
            <td class="py-0" colspan="1">
              <Label class="col-form-label col-form-label-sm text-primary">{{ data.username }}</Label>
            </td>
            <td class="py-0" colspan="1">{{ data.created_at }}</td>
            <td class="py-0" colspan="1">Stock#: {{ data.salespersonstockno }}</td>
          </tr>
          <tr>
            <td class="py-0" colspan="4">
              <label class="col-form-label col-form-label-sm text-primary">
                <div class="mr-4 float-left">{{ data.frdkeys }}</div>
                <div class="mr-4 float-left">{{ data.chyskimreset }}</div>
                <div class="mr-4 float-left">{{ data.chydonorinfo }}</div>
                <div class="mr-4 float-left">{{ data.chydonorinfo2 }}</div>
                <div class="mr-4 float-left">{{ data.chycustinfo }}</div>
                <div class="mr-4 float-left">{{ data.chycustinfo2 }}</div>
                <div class="mr-4 float-left">{{ data.general ? 'Programmed No VIN' : '' }}</div>
                <div class="mr-4 float-left" v-for="(issue, index) in showIssues" :key="index">{{ issue }}</div>
              </label>
            </td>
          </tr>
          <tr>
            <td class="py-0 word-break" colspan="4">
              <label class="col-form-label col-form-label-sm" v-html="data.programmingnotes"/>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      default: null,
    },
    pgEntries: {
      type: Array,
      default: null
    },
    index: {
      type: Number,
      default: 0,
    },
    headerStyle: String,
  },
  computed: {
    showIssues() {
      let issues = [];

      issues.push(this.data.doa ? 'issue:DOA' : '');
      issues.push(this.data.wronghw ? 'issue:Wrong H/W,' + ' Right H/W=' + this.data.correcthwneeded : '');
      issues.push(this.data.needsw ? 'issue:Need S/W' : '');
      issues.push(this.data.wrongecuhw ? 'issue:Wrong ECU style, wrong H/W' : '');
      issues.push(this.data.wrongparttype ? 'issue:Wrong Part Type' : '');
      issues.push(this.data.purchaseinfomismatch ? 'issue:Purchase Info Mismatch' : '');
      issues.push(this.data.securitymismatch ? 'issue:Security Missmatch' : '');
      issues.push(this.data.badlydamagedunit ? 'issue:BadlyDamagedUnit' : '');
      issues.push(this.data.needswpn ? 'issue:Need Software P/N from Cust' : '');
      issues.push(this.data.needcustvin ? 'issue:Need Cust VIN' : '');
      issues.push(this.data.needonboardtesting ? 'issue:Need Onboard Testing' : '');

      return issues;
    },
  },
  methods: {
    edit() {
      this.$emit('edit', this.data, this.index);
    },
  },
};
</script>
