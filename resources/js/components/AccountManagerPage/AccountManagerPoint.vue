<template>
  <div class="card">
    <div class="card-header py-1 bg-gray-700 text-light theme-color">
      <strong>
        <i class="fa fa-edit"></i>
        {{ `${userName}'s Points` }}
      </strong>
    </div>
    <div class="card-body">
      <div class="container-fluid">
        <div class="form-inline">
          <div class="form-group mr-5">
            <label for="programming-from" class="col-form-control mr-3">From</label>
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
          <button @click="createCsv" class="btn btn-primary ml-auto my-1">Download CSV</button>
        </div>
        <table v-if="hasData" class="table table-sm table-bordered table-striped table-hover my-3">
          <thead>
            <tr>
              <td class="py-1">Account</td>
              <td class="py-1">Type</td>
              <td class="py-1">Total</td>
              <td class="py-1">Date</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(point, index) in managerPoints.data" :key="index">
              <td class="py-1">{{ point.account_id }}</td>
              <td class="py-1">{{ point.type }}</td>
              <td class="py-1">{{ point.total }}</td>
              <td class="py-1">{{ point.created_at }}</td>
            </tr>
          </tbody>
        </table>
        <pagination
          :data="managerPoints"
          @pagination-change-page="getData"
          size="small"
          align="right"
          :limit="1"
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
  props: ['user-name', 'date-from', 'date-to'],
  data() {
    return {
      from: this.dateFrom,
      to: this.dateTo,
      managerPoints: {},
      csvData: [],
    };
  },
  created() {
    this.getData();
  },
  computed: {
    hasData() {
      return (
        this.managerPoints.data !== undefined &&
        this.managerPoints.data.length > 0
      );
    },
  },
  methods: {
    getData(page = 0) {
      const params = {
        from: this.from,
        to: this.to,
        name: this.userName,
      };
      if (page) {
        params.page = page;
      }
      api
        .accountManagerPoints({ params })
        .then(res => {
          this.managerPoints = res.data;
        })
        .catch(err => {});
    },

    createCsv() {
      const params = {
        from: this.from,
        to: this.to,
        name: this.userName,
      };
      api
        .accountManagerPointsDownload({ params })
        .then(res => {
          this.csvData = res.data.data;
          this.downloadCSV();
        })
        .catch(err => {});
    },
    downloadCSV() {
      let arrData = this.csvData;
      // let arrData = this.managerPoints.data;
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
