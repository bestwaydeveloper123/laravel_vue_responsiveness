<template>
  <div>
    <div class="card">
      <div class="card-header bg-gray-700 py-1 text-light theme-color">
        <strong>
          <i class="fa fa-edit"></i> Shipping
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
                v-model="startDate"
                class="form-control form-control-sm"
                placeholder="start date"
              />
            </div>
            <div class="form-group mr-3">
              <label for="shipping-to" class="col-form-control mr-3">To</label>
              <input
                id="shipping-to"
                type="date"
                v-model="endDate"
                class="form-control form-control-sm"
                placeholder="end date"
              />
            </div>
            <button @click="getData" class="btn btn-primary my-1">Search</button>
            <button @click="createCsv" class="btn btn-primary ml-auto my-1">Download CSV</button>
          </div>
          <div class="row col-12">
            <table id="shipping" class="table col-12" style="width:100%">
              <thead v-if="shippingArray.length > 0">
                <tr>
                  <th>Username</th>
                  <th>Total Points</th>
                  <th>Total Parts</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in shippingArray" :key="user.id">
                  <td>
                    <a :href="`/admin/points/shipping/${user.UserId}`">
                      {{
                      user.username
                      }}
                    </a>
                  </td>
                  <td>{{ user.TotalPoints }}</td>
                  <td>{{ user.TotalParts }}</td>
                </tr>
              </tbody>
              <tr v-if="shippingArray.length == 0" class="font-weight-bold m-auto">
                <td class="text-center" colspan="3">No data found</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header bg-gray-700 py-1 text-light theme-color">
        <strong>
          <i class="fa fa-edit"></i> Team Stats
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
                v-model="startDate"
                class="form-control form-control-sm"
                placeholder="start date"
              />
            </div>
            <div class="form-group mr-3">
              <label for="shipping-to" class="col-form-control mr-3">To</label>
              <input
                id="shipping-to"
                type="date"
                v-model="endDate"
                class="form-control form-control-sm"
                placeholder="end date"
              />
            </div>
            <button @click="getTeamData" class="btn btn-primary my-1">Search</button>
            <!-- <button @click="createCsv" class="btn btn-primary ml-auto my-1">Download CSV</button> -->
          </div>
          <div class="row col-12">
            <table id="shipping" class="table col-12" style="width:100%">
              <thead v-if="teamArray.length > 0">
                <tr>
                  <th>Username</th>
                  <th>Parts Finished</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in teamArray" :key="user.id">
                  <td>
                    <!-- <a :href="`/admin/points/shipping/${user.UserId}`"> -->
                      {{
                      user.username
                      }}
                    <!-- </a> -->
                  </td>
                  <td>{{ user.TotalPoints }}</td>
                </tr>
              </tbody>
              <tr v-if="teamArray.length == 0" class="font-weight-bold m-auto">
                <td class="text-center" colspan="3">No data found</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import api from '../../api';
export default {
  name: 'ShippingPage',
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
      shippingArray: [],
      teamArray: [],
    };
  },
  mounted() {
    this.getData();
    this.getTeamData();
  },
  methods: {
    getData() {
      let obj = {
        startDate: this.startDate,
        fromDate: this.endDate,
        page: 0,
      };
      api
        .shippingList(obj)
        .then(res => {
          if (res.data.IsSuccess) this.shippingArray = res.data.Data;
        })
        .catch(err => console.log(err));
    },
    getTeamData() {
      let obj = {
        startDate: this.startDate,
        fromDate: this.endDate,
        page: 0,
      };
      api
        .shippingListCount(obj)
        .then(res => {
          if (res.data.IsSuccess) this.teamArray = res.data.Data;
        })
        .catch(err => console.log(err));
    },
    createCsv() {
      let arrData = this.shippingArray;
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
