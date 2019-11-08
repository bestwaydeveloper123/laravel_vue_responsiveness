<template>
  <div class="card">
    <div class="card-header bg-gray-700 text-light theme-color py-1">
      <strong><i class="fa fa-edit"></i> Programming</strong>
    </div>
    <div class="card-body">
      <div class="container-fluid">
        <div class="form-inline">
          <div class="form-group mr-5">
            <label for="programming-from" class="col-form-control mr-3"
              >From</label
            >
            <input
              id="programming-from"
              type="date"
              class="form-control form-control-sm"
              v-model="from"
            />
          </div>
          <div class="form-group mr-3">
            <label for="programming-to" class="col-form-control mr-3">To</label>
            <input
              id="programming-to"
              type="date"
              class="form-control form-control-sm"
              v-model="to"
            />
          </div>
          <button @click="getData" class="btn btn-primary my-1">Search</button>
        </div>
        <table
          v-if="hasData"
          class="table table-sm table-bordered table-striped table-hover my-3"
        >
          <thead>
            <tr>
              <td class="py-1 text-center" width="34%">User Name</td>
              <td v-if="role != 3" class="py-1 text-center" width="33%">Total Points</td>
              <td class="py-1 text-center" width="33%">Total Parts</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(entry, index) in programmed.Data" :key="index">
              <td class="py-1 text-center">
                <a
                  class="text-secondary"
                  :href="
                    `/admin/points/program-user${makeParam(entry.UserId)}`
                  "
                  >{{ entry.username }}</a
                >
              </td>
              <td v-if="role != 3" class="py-1 text-center">{{ entry.TotalPoints }}</td>
              <td class="py-1 text-center">{{ entry.TotalParts }}</td>
            </tr>
          </tbody>
        </table>
        <pagination
          :data="programmed"
          @pagination-change-page="getData"
          size="small"
          align="right"
          :limit="2"
        ></pagination>
      </div>
    </div>
  </div>
</template>

<script>
import Api from '../../api';
import Pagination from 'laravel-vue-pagination';

export default {
  name: 'ProgramPage',
  components: {
    Pagination,
  },
  props: {
    role: String
  },
  data() {
    return {
      programmed: {},
      from: '',
      to: '',
    };
  },
  created() {
    this.getData();
  },
  computed: {
    hasData() {
      return (
        this.programmed.Data !== undefined && this.programmed.Data.length > 0
      );
    },
  },
  methods: {
    getData(page = 0) {
      let params = {
        startDate: this.from,
        fromDate: this.to,
        page: 0
      };
      // if (page) {
      //   params.page = page;
      // }
      Api.programmerList(
        params
      )
        .then(res => {
          this.programmed = res.data;
        })
        .catch(err => {});
    },
    makeParam(user) {
      return `?user-id=${user}&from=${this.from}&to=${this.to}`;
    },
    entryData(entry) {
      let showData = '';
      showData += entry.entrytype ? entry.entrytype + ', ' : '';
      showData += entry.salespersonstockno
        ? entry.salespersonstockno + ', '
        : '';
      showData += entry.frdkeys ? entry.frdkeys + ', ' : '';
      showData += entry.chyskimreset ? entry.chyskimreset + ', ' : '';
      showData += entry.chydonorinfo ? entry.chydonorinfo + ', ' : '';
      showData += entry.chydonorinfo2 ? entry.chydonorinfo2 + ', ' : '';
      showData += entry.chycustinfo ? entry.chycustinfo + ', ' : '';
      showData += entry.chycustinfo2 ? entry.chycustinfo2 + ', ' : '';
      showData += entry.general ? 'GENERAL, ' : '';
      showData += entry.doa ? 'DOA, ' : '';
      showData += entry.wronghw ? 'Correct ECU style-Wrong H/W, ' : '';
      // showData += entry.needsw ? '' : '';
      showData += entry.needswpn ? 'Need Software P/N from Cust, ' : '';
      showData += entry.needcustvin ? 'Need Cust VIN, ' : '';
      showData += entry.vendorsentincorrect
        ? 'Vendor Sent Incorrect ECU Type/Style OR Purchase Info Mismatch, '
        : '';
      return showData;
    },
  },
};
</script>
