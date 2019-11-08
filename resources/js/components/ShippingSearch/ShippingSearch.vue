<template>
<div class="row">
  <div class="col-md-6 col-sm-12 card mb-0">
    <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
      <strong>
        <i class="fa fa-edit"></i> Shipping
      </strong>
    </div>
    <div class="card-body pb-0 pt-0 pl-0 pr-0">
      <table class="table table-bordered bg-gray-200 mb-0">
        <tr>
          <td>
            <input
              type="text"
              class="form-control"
              v-model="searchTerm"
              placeholder="Enter Shipping"
            />
          </td>
        </tr>
        <tr>
          <td>
            <select v-model="userid" type="text" class="form-control" name="username">
              <option v-for="(user) in userDetails" :key="user.id" :value="user.id">{{user.username}}</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <input class="form-control bg-success" @click="search" type="submit" value="Submit" />
          </td>
        </tr>
      </table>
    </div>
  </div>
  <div class="col-md-6 col-sm-12 card mb-0">
      <searchpage :searchResult="searchResult"></searchpage>
  </div>
  </div>
</template>

<script>
import api from '../../api';
import searchpage from './SearchResult';

export default {
  name: 'ShippingSearch',
  components: {
    searchpage
  },
  props: {
    users: {
      type: String,
    },
  },
  data() {
    return {
        userDetails: [],
        userid:'',
        searchTerm:'',
        searchResult:[]
    };
  },
  mounted(){
      this.userDetails = JSON.parse(this.users);
  },
  methods: {
      search(){
          let obj ={
              'inventory_id': this.searchTerm,
              'user_id': this.userid
          }

          api
          .ShippingSearchResult(obj)
          .then(res => {
            if (res.data) {
              this.searchResult = res.data.Data.Shipping;
            }
          })
          .catch(err => console.log(err));
      }
  },
};
</script>


