<template>
  <div class="card">
    <div class="card-header bg-gray-700 py-1 text-light theme-color">
      <strong>
        <i class="fa fa-edit"></i> Account Manager
      </strong>
    </div>
    <div class="card-body">
      <div class="container-fluid">
        <div class="form-inline">
          <div class="form-group mr-5">
            <label for="shipping-from" class="col-form-control mr-3">From</label>
            <input
              id="shipping-from"
              type="date"
              v-model="from"
              class="form-control form-control-sm"
            />
          </div>
          <div class="form-group mr-3">
            <label for="shipping-to" class="col-form-control mr-3">To</label>
            <input id="shipping-to" type="date" v-model="to" class="form-control form-control-sm" />
          </div>
          <button @click="getData" class="btn btn-primary my-1">Search</button>
          <button @click="createCsv" class="btn btn-primary ml-auto my-1">Download CSV</button>
        </div>
        <div class="row col-12">
          <table
            v-if="hasData"
            class="table table-sm table-bordered table-striped table-hover my-3"
          >
            <thead>
              <tr>
                <th @click="sort('user_name')" class="py-0 text-center cursor-pointer text-blue">Username</th>
                <th @click="sort('totalPoints')" class="py-0 text-center cursor-pointer text-blue">Total Points</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(user, index) in sortedManagerList" :key="index">
                <td class="py-1 text-center">
                  <a
                    class="text-secondary"
                    :href="
                      `/admin/points/account-manager-sel${makeParam(
                        user.user_name,
                      )}`
                    "
                  >{{ user.user_name }}</a>
                </td>
                <td class="py-1 text-center">{{ parseFloat(user.totalPoints).toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import api from '../../api';

export default {
  name: 'AccountManagePage',

  props: {},
  data() {
    return {
      from: '',
      to: '',
      managerList: [],
      csvData: [],
      currentSort: 'user_name',
      currentSortDir: 'asc',
    };
  },
  created() {
    this.getData();
  },
  computed: {
    hasData() {
      return this.managerList !== undefined && this.managerList.length > 0;
    },
    sortedManagerList: function() {
      return this.managerList.sort((a, b) => {
        let modifier = 1;
        if (this.currentSortDir === 'desc') modifier = -1;
        if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
        if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
        return 0;
      });
    },
  },

  methods: {
    sort: function(s) {
      //if s == current sort, reverse
      if (s === this.currentSort) {
        this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
      }
      this.currentSort = s;
    },
    getData(page = 0) {
      const params = {
        from: this.from,
        to: this.to,
      };
      api
        .accountManagerList({ params })
        .then(res => {
          this.managerList = res.data;
        })
        .catch(err => {});
    },
    makeParam(user) {
      return `?name=${user}&from=${this.from}&to=${this.to}`;
    },
    createCsv() {
      const params = {
        from: this.from,
        to: this.to,
      };
      api
        .accountManagerListDownload({ params })
        .then(res => {
          this.csvData = res.data.data;
          this.downloadCSV();
        })
        .catch(err => {});
    },
    downloadCSV() {
      let arrData = this.csvData;
      let csvContent = 'data:text/csv;charset=utf-8,';
      csvContent += [
        Object.keys(arrData[0]).join(','),
        ...arrData.map(item => Object.values(item).join(',')),
      ]
        .join('\n')
        .replace(/(^\[)|(\]$)/gm, '');
      const data = encodeURI(csvContent);
      const link = document.createElement('a');
      link.setAttribute('href', data);
      link.setAttribute('download', 'export.csv');
      link.click();
    },
  },
};
</script>
