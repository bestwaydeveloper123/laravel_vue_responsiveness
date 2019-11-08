<template>
  <div class="card">
    <div class="card-header py-1 bg-gray-700 text-light theme-color">
      <!-- <strong><i class="fa fa-edit"></i> {{ `${userName}'s Entries` }}</strong> -->
      <strong><i class="fa fa-edit"></i> Entries</strong>
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
              <!-- <td class="py-1">Entry Type</td>
              <td class="py-1">Account</td>
              <td class="py-1">Stock #</td>
              <td class="py-1">FRD Keys</td>
              <td class="py-1">CHY Skim Reset</td>
              <td class="py-1">CHY Donor Info</td>
              <td class="py-1">CHY Cust Info</td>
              <td class="py-1">General</td>
              <td class="py-1">Date</td> -->
              <td class="py-1">Username</td>
              <td class="py-1">Account Id</td>
              <td class="py-1">Task</td>
              <td class="py-1">Points</td>
              <td class="py-1">Date</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(entry, index) in programmed.Data" :key="index">
              <!-- <td class="py-1">{{ entry.entrytype }}</td>
              <td class="py-1">{{ entry.account_id }}</td>
              <td class="py-1">{{ entry.salespersonstockno }}</td>
              <td class="py-1">{{ entry.frdkeys }}</td>
              <td class="py-1">{{ entry.chyskimreset }}</td>
              <td class="py-1">{{ getChydonorinfo(entry) }}</td>
              <td class="py-1">{{ getChycustinfo(entry) }}</td>
              <td class="py-1">{{ getGeneral(entry) }}</td>
              <td class="py-1">{{ entry.created_at }}</td> -->
              <td class="py-1">{{ user }}</td>
              <td class="py-1">{{ entry.account_id }}</td>
              <td class="py-1">{{ entry.task }}</td>
              <td class="py-1">{{ entry.points }}</td>
              <td class="py-1">{{ entry.created_at }}</td>
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
import Pagination from 'laravel-vue-pagination';
import api from '../../api';

export default {
  name: 'ProgramUser',
  components: {
    Pagination,
  },
  props: ['user-id', 'date-from', 'date-to'],
  data() {
    return {
      from: this.dateFrom,
      to: this.dateTo,
      id: '',
      programmed: {},
      user: ''
    };
  },
  created() {
    var urlParams = new URLSearchParams(window.location.search);
    this.id = urlParams.get('user-id');
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
      const params = {
        startDate: this.from,
        fromDate: this.to,
        id: this.id,
        page: 0
      };
      // if (page) {
      //   params.page = page;
      // }
      api
        .programmerByUser(params  )
        .then(res => {
          this.programmed = res.data;
          this.user = res.data.UserName;
        })
        .catch(err => {});
    },
    getChydonorinfo(entry) {
      const info = entry.chydonorinfo ? `${entry.chydonorinfo}, ` : '';
      return `${info}${entry.chydonorinfo1 || ''}`;
    },
    getChycustinfo(entry) {
      const info = entry.chycustinfo ? `${entry.chycustinfo}, ` : '';
      return `${info}${entry.chycustinfo1 || ''}`;
    },
    getGeneral(entry) {
      return entry.general ? 'Programmed No VIN' : '';
    },
  },
};
</script>
