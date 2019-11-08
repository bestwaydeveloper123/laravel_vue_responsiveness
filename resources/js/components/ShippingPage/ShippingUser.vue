<template>
  <div class="card mb-0">
    <!-- <h3>User Name:{{userName}}</h3> -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-3 form-group">
          <label>From</label>
          <input type="date" v-model="startDate" class="form-control" placeholder="start date">
        </div>

        <div class="col-3 form-group">
          <label>To</label>
          <input type="date" v-model="endDate" class="form-control" placeholder="end date">
        </div>
      </div>
      <div class="row col-12">
        <button @click="getData" class="btn btn-primary m-1">Search</button>
        <button @click="createCsv" class="btn btn-primary m-1">Download CSV</button>
      </div>
      <div class="row col-12">
        <table class="table" style="width:100%">
          <thead>
            <tr>
              <th>Username</th>
              <th>Account Id</th>
              <th>Task</th>
              <th>Points</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody v-if="taskList.length>0">
            <tr v-for="(task, index) in taskList" :key="index">
              <td>{{userName}}</td>
              <td>{{task.account_id}}</td>
              <td>{{task.task}} <span class="text-red">{{ task.OT? '(OT)' : '' }}</span></td>
              <td>{{task.points}}</td>
              <td>{{task.created_at}}</td>
            </tr>
          </tbody>
          <tr v-if="taskList.length==0" class="font-weight-bold m-auto"><td class="text-center" colspan="3">No data found</td></tr>
        </table>
      </div>
    </div>
  </div>
</template>
<script>
import api from '../../api';
export default {
  name: 'ShippingUser',
  props: {
    id: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      startDate: '',
      endDate: '',
      userName: '',
      taskList: [],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      let obj = {
        id: this.id,
        startDate: this.startDate,
        fromDate: this.endDate,
        page: 0,
      };
      api
        .shippingByUser(obj)
        .then(res => {
          if (res.data.IsSuccess) {
            this.taskList = res.data.Data;
            this.userName = res.data.UserName;
          }
        })
        .catch(err => console.log(err));
    },
    createCsv() {
      let arrData = [...this.taskList];
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

<style lang="sass" scoped>

</style>
