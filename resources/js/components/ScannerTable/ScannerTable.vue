<template>
  <div>
    <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
      <strong>History</strong>
    </div>
    <!-- <input type="text" class="form-control m-10" placeholder="S#" v-model="sIdToSearch"> -->
    <table class="table border-1">
      <thead>
        <tr>
          <th scope="col">User</th>
          <th scope="col">Action</th>
          <th scope="col">Date</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(history, index) in historyData" :key="index">
          <td>{{history.username}}</td>
          <td>{{history.action}}</td>
          <td>{{history.created_at}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import api from '../../api';

export default {
  name: 'ScannerTable',
  props: {
    scannerHistory: {
      type: String,
      default: '',
    },
    searchTerm: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      historyData: [],
      timer: '',
    };
  },
  mounted() {
    // this.historyData = JSON.parse(this.scannerHistory);
    // console.log('searchTerm',this.searchTerm)
    this.actUpdate()
  },
  created: function() {
    this.timer = setInterval(this.actUpdate, 1000);
  },
  methods: {
    actUpdate: function() {
      let obj = {
        'inventories_id': this.searchTerm
      }
      api
        .scannerHistory(obj)
        .then(res => {
          this.historyData = res.data
        })
        .catch(err => console.log(err));
    },
  },
  beforeDestroy() {
    clearInterval(this.timer);
  },
};
</script>


<style lang="scss" scoped>
table {
  text-align: center;
  padding: 2px;
}
</style>

